<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'provider_id',
        'tax',
        'exempt',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function paymentOrder()
    {
        return $this->hasOne(PaymentOrder::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
