<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MpesaCallbackController extends Controller
{
    public function handle(Request $request, MpesaService $mpesa)
    {
        $callback = $request->input('Body.stkCallback');

        if (!is_array($callback)) {
            Log::warning('Invalid M-Pesa callback received.');

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Invalid callback',
            ]);
        }

        $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;
        $resultCode = $callback['ResultCode'] ?? null;

        if (!$checkoutRequestId || $resultCode === null) {
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Missing callback details',
            ]);
        }

        $payment = Payment::where(
            'checkout_request_id',
            $checkoutRequestId
        )->first();

        if (!$payment) {
            Log::warning('M-Pesa callback did not match a payment.');

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback received',
            ]);
        }

        // Do not let an unverified callback mark a payment as paid or failed.
        if ((string) $resultCode !== '0') {
            Log::info('M-Pesa reported an unsuccessful payment.', [
                'payment_id' => $payment->id,
                'result_code' => $resultCode,
                'description' => $callback['ResultDesc'] ?? '',
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback received',
            ]);
        }

        $items = collect(
            $callback['CallbackMetadata']['Item'] ?? []
        )->keyBy('Name');

        $mpesaReceipt = $items->get('MpesaReceiptNumber')['Value'] ?? null;
        $amount = $items->get('Amount')['Value'] ?? null;
        $phone = $items->get('PhoneNumber')['Value'] ?? null;

        $phone = $phone !== null
            ? preg_replace('/\D/', '', (string) $phone)
            : null;

        // Require the transaction details expected for this payment.
        if (
            !$mpesaReceipt ||
            $amount === null ||
            $phone === null ||
            (float) $amount !== (float) $payment->amount ||
            $phone !== $payment->phone_number
        ) {
            Log::warning('M-Pesa callback details did not match the payment.', [
                'payment_id' => $payment->id,
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback received; verification required',
            ]);
        }

        // Independently ask Safaricom for the status of this STK request.
        try {
            $verification = $mpesa->queryStkStatus($checkoutRequestId);
        } catch (Throwable $exception) {
            Log::error('M-Pesa status verification request failed.', [
                'payment_id' => $payment->id,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback received; verification is pending',
            ]);
        }

        $verified =
            (string) ($verification['ResultCode'] ?? '') === '0'
            && ($verification['CheckoutRequestID'] ?? '') === $checkoutRequestId;

        if (!$verified) {
            Log::warning('Safaricom has not confirmed successful payment.', [
                'payment_id' => $payment->id,
                'verification_result' => $verification['ResultCode'] ?? null,
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Verification is pending',
            ]);
        }

        DB::transaction(function () use ($payment, $mpesaReceipt, $callback) {
            $lockedPayment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedPayment || $lockedPayment->status === 'paid') {
                return;
            }

            $lockedPayment->status = 'paid';
            $lockedPayment->mpesa_receipt_number = $mpesaReceipt;
            $lockedPayment->mpesa_result_code = '0';
            $lockedPayment->mpesa_result_description =
                $callback['ResultDesc'] ?? 'Payment verified';
            $lockedPayment->paid_at = now();

            // Generate a customer-facing receipt only after verification.
            if (
                str_starts_with(
                    (string) $lockedPayment->receipt_number,
                    'PENDING-'
                )
            ) {
                $lockedPayment->receipt_number =
                    'CS-' . now()->format('Ymd') . '-'
                    . Str::upper(Str::random(8));
            }

            $lockedPayment->save();
        });

        Log::info('M-Pesa payment verification completed.', [
            'payment_id' => $payment->id,
        ]);

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback processed',
        ]);
    }
}

