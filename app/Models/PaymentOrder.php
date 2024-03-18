<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_reference',
        'bank',
        'account_number',
        'description',
        'payment_number'
    ];

    public function invoice()
    {
        return $this->belongsTo(Order::class);
    }
}
