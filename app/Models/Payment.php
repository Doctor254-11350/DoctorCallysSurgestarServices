<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'receipt_number',
        'service_name',
        'amount',
        'phone_number',
        'status',
        'mpesa_receipt_number',
        'paid_at',
        'merchant_request_id',
        'checkout_request_id',
        'mpesa_result_code',
        'mpesa_result_description',
        'paystack_reference',
        'paystack_transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}




