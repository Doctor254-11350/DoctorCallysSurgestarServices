<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystackService
{
    private function secretKey(): string
    {
        $key = config('services.paystack.secret_key');

        if (!$key) {
            throw new RuntimeException(
                'Paystack secret key is missing.'
            );
        }

        return $key;
    }

    public function chargeMpesa(
        string $email,
        string $phone,
        int $amount,
        string $reference,
        int $paymentId
    ): array {
        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->timeout(45)
            ->post('https://api.paystack.co/charge', [
                'email' => $email,
                'amount' => $amount * 100,
                'currency' => 'KES',

                'mobile_money' => [
                    'phone' => $phone,
                    'provider' => 'mpesa',
                ],

                'reference' => $reference,

                'metadata' => [
                    'payment_id' => $paymentId,
                ],
            ]);

       $result = $response->json();

if (!$response->successful() || !($result['status'] ?? false)) {
    throw new RuntimeException(
        'Paystack HTTP ' . $response->status() . ': ' .
        json_encode($result)
    );
}

return $result;
    }

    public function verifyTransaction(string $reference): array
    {
        $response = Http::withToken($this->secretKey())
            ->acceptJson()
            ->timeout(30)
            ->get(
                'https://api.paystack.co/transaction/verify/'
                . urlencode($reference)
            );

        $response->throw();

        return $response->json();
    }
}