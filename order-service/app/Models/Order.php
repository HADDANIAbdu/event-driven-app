<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'restaurant_id',
        'items',
        'address',
        'total',
        'status',
        'placed_at',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
