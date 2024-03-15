<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'item_name',
        'item_quantity',
        'item_amount',
        'total_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
}
