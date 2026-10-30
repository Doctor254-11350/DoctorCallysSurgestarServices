<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request, PaystackService $paystack)
    {
        $secret = config('services.paystack.secret_key');

        $signature = $request->header('x-paystack-signature');

        if (!$secret || !$signature) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $expectedSignature = hash_hmac(
            'sha512',
            $request->getContent(),
            $secret
        );

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $event = $request->json()->all();

        if (($event['event'] ?? '') !== 'charge.success') {
            return response()->json(['message' => 'Event ignored']);
        }

        $data = $event['data'] ?? [];

        $reference = $data['reference'] ?? null;

        if (!$reference) {
            return response()->json(['message' => 'Missing reference'], 400);
        }

        $payment = Payment::where(
            'paystack_reference',
            $reference
        )->first();

        if (!$payment) {
            Log::warning('Paystack payment reference not found.', [
                'reference' => $reference,
            ]);

            return response()->json(['message' => 'Payment not found'], 404);
        }

        try {
            $verification = $paystack->verifyTransaction($reference);

            $transaction = $verification['data'] ?? [];

            $valid =
                ($verification['status'] ?? false) === true
                && ($transaction['status'] ?? '') === 'success'
                && ($transaction['reference'] ?? '') === $reference
                && ($transaction['currency'] ?? '') === 'KES'
                && (int) ($transaction['amount'] ?? 0)
                    === ((int) $payment->amount * 100);

            if (!$valid) {
                Log::warning('Paystack payment verification failed.', [
                    'payment_id' => $payment->id,
                    'reference' => $reference,
                ]);

                return response()->json([
                    'message' => 'Payment verification failed',
                ], 400);
            }

            DB::transaction(function () use ($payment, $transaction) {
                $lockedPayment = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedPayment || $lockedPayment->status === 'paid') {
                    return;
                }

                $lockedPayment->status = 'paid';

                $lockedPayment->paystack_transaction_id =
                    (string) $transaction['id'];

                $lockedPayment->mpesa_result_code = '0';

                $lockedPayment->mpesa_result_description =
                    'Payment verified by Paystack';

                $lockedPayment->paid_at = now();

                $lockedPayment->receipt_number =
                    'CS-' . now()->format('Ymd') . '-'
                    . Str::upper(Str::random(8));

                $lockedPayment->save();
            });

            return response()->json([
                'message' => 'Payment processed successfully',
            ]);

        } catch (Throwable $exception) {

            Log::error('Paystack webhook verification error.', [
                'payment_id' => $payment->id,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Verification temporarily unavailable',
            ], 500);
        }
    }
}