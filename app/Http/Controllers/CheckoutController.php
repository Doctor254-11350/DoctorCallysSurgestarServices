<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckoutController extends Controller
{
    private function services(): array
    {
        return [
            'education' => [
                'name' => 'Educational Resources',
                'description' => 'Access educational websites, courses and study materials.',
                'price' => 200,
            ],

            'programming' => [
                'name' => 'Programming & Coding',
                'description' => 'Learn programming, coding languages and software development.',
                'price' => 200,
            ],

            'website' => [
                'name' => 'Website Development',
                'description' => 'Explore website creation, hosting and development tools.',
                'price' => 1500,
            ],

            'online' => [
                'name' => 'Online Services',
                'description' => 'Discover useful online tools and digital services.',
                'price' => 100,
            ],
        ];
    }

    public function index(Request $request)
    {
        $services = $this->services();

        $selectedService = $request->query('service');

        if (!array_key_exists($selectedService, $services)) {
            $selectedService = '';
        }

        return view('checkout', compact(
            'services',
            'selectedService'
        ));
    }

    public function store(Request $request, PaystackService $paystack)
    {
        $services = $this->services();

        $validated = $request->validate([
            'service' => [
                'required',
                'in:education,programming,website,online',
            ],

            'phone_number' => [
                'required',
                'regex:/^(?:07|01)[0-9]{8}$|^254(?:7|1)[0-9]{8}$|^\+254(?:7|1)[0-9]{8}$/',
            ],
        ]);

        $service = $services[$validated['service']];

        $phone = preg_replace('/\D/', '', $validated['phone_number']);

        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }

        $phone = '+' . $phone;

        $reference = 'CS' . strtoupper(Str::random(18));

        $payment = Payment::create([
            'user_id' => $request->user()->id,
            'receipt_number' => 'PENDING-' . Str::upper(Str::random(12)),
            'service_name' => $service['name'],
            'amount' => $service['price'],
            'phone_number' => $phone,
            'status' => 'pending',
            'paystack_reference' => $reference,
        ]);

        try {
            $result = $paystack->chargeMpesa(
                $request->user()->email,
                $phone,
                (int) $service['price'],
                $reference,
                $payment->id
            );

            $data = $result['data'] ?? [];

            return redirect()
                ->route('checkout', ['service' => $validated['service']])
                ->with(
                    'info',
                    $data['display_text']
                        ?? 'Check your phone and authorize the M-Pesa payment using your PIN.'
                )
                ->with('payment_reference', $reference);

        } catch (Throwable $exception) {

            Log::error('Paystack payment request failed.', [
                'payment_id' => $payment->id,
                'error' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('checkout', ['service' => $validated['service']])
                ->with(
                    'error',
                    'We could not start your M-Pesa payment. Please try again.'
                );
        }
    }
}