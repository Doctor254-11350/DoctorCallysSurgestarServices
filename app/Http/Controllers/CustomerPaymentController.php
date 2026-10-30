<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
    /**
     * Display the customer's dashboard and payment history.
     */
    public function dashboard(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('dashboard', compact('payments'));
    }

    /**
     * Display an individual payment receipt.
     */
    public function receipt(Request $request, Payment $payment)
    {
        // Customers can only access their own payments.
        abort_unless(
            $payment->user_id === $request->user()->id,
            404
        );

        // A pending or failed payment is not a valid receipt.
        abort_unless($payment->status === 'paid', 404);

        return view('receipt', compact('payment'));
    }
}