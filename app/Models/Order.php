<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id','order_ref','first_name','last_name','email','phone',
        'company','address_line1','address_line2','city','postcode','country',
        'delivery_notes','delivery_method','payment_method',
        'subtotal','vat','delivery_cost','total','status',
    ];

    protected static function booted(): void
    {
        static::creating(function ($order) {
            $order->order_ref = $order->order_ref ?? 'LIP-' . strtoupper(Str::random(8));
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
