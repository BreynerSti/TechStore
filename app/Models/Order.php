<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
  protected $fillable = [
    'order_number',
    'user_id',
    'status',
    'subtotal',
    'tax',
    'shipping',
    'total',
    'shipping_name',
    'shipping_email',
    'shipping_phone',
    'shipping_address',
    'shipping_city',
    'shipping_postal_code',
    'payment_method',
    'payment_status',
    'notes',
];

// Relaciones
public function user()
{
    return $this->belongsTo(User::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

public function items()
{
    return $this->hasMany(OrderItem::class);
}
}
