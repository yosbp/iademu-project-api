<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'rif',
        'address',
        'phone',
    ];

    public function invoices()
    {
        return $this->hasMany(Order::class);
    }
}
