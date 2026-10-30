<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MpesaService
{
    /**
     * Choose the Safaricom API environment.
     */
    private function baseUrl(): string
    {
        return config('services.mpesa.environment') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Obtain an OAuth access token from Safaricom.
     */
    public function getAccessToken(): string
    {
        $consumerKey = config('services.mpesa.consumer_key');
        $consumerSecret = config('services.mpesa.consumer_secret');

        if (!$consumerKey || !$consumerSecret) {
            throw new RuntimeException(
                'M-Pesa Consumer Key or Consumer Secret is missing.'
            );
        }

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->acceptJson()
            ->timeout(30)
            ->get($this->baseUrl() . '/oauth/v1/generate', [
                'grant_type' => 'client_credentials',
            ]);

        $response->throw();

        $token = $response->json('access_token');

        if (!$token) {
            throw new RuntimeException(
                'Safaricom did not return an access token.'
            );
        }

        return $token;
    }

    /**
     * Request an M-Pesa STK Push on the customer's phone.
     */
    public function stkPush(
        string $phone,
        int $amount,
        string $accountReference,
        string $description
    ): array {
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');
        $callbackUrl = config('services.mpesa.callback_url');

        if (!$shortcode || !$passkey) {
            throw new RuntimeException(
                'M-Pesa shortcode or passkey is missing.'
            );
        }

        if (!$callbackUrl || !str_starts_with($callbackUrl, 'https://')) {
            throw new RuntimeException(
                'A public HTTPS M-Pesa callback URL is required.'
            );
        }

        $timestamp = now()->format('YmdHis');

        $password = base64_encode(
            $shortcode . $passkey . $timestamp
        );

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->timeout(30)
            ->post(
                $this->baseUrl() . '/mpesa/stkpush/v1/processrequest',
                [
                    'BusinessShortCode' => $shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => $amount,
                    'PartyA' => $phone,
                    'PartyB' => $shortcode,
                    'PhoneNumber' => $phone,
                    'CallBackURL' => $callbackUrl,
                    'AccountReference' => substr($accountReference, 0, 12),
                    'TransactionDesc' => substr($description, 0, 13),
                ]
            );

        $response->throw();

        return $response->json();
    }

    /**
     * Query the status of an existing STK Push request.
     */
    public function queryStkStatus(string $checkoutRequestId): array
    {
        $shortcode = config('services.mpesa.shortcode');
        $passkey = config('services.mpesa.passkey');

        if (!$shortcode || !$passkey) {
            throw new RuntimeException(
                'M-Pesa shortcode or passkey is missing.'
            );
        }

        if ($checkoutRequestId === '') {
            throw new RuntimeException(
                'The Checkout Request ID is required.'
            );
        }

        $timestamp = now()->format('YmdHis');

        $password = base64_encode(
            $shortcode . $passkey . $timestamp
        );

        $response = Http::withToken($this->getAccessToken())
            ->acceptJson()
            ->timeout(30)
            ->post(
                $this->baseUrl() . '/mpesa/stkpushquery/v1/query',
                [
                    'BusinessShortCode' => $shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]
            );

        $response->throw();

        return $response->json();
    }
}

