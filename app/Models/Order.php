<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'client_name',
        'client_phone',
        'client_email',
        'total_price',
        'status',
    ];

    // Также рекомендую сразу добавить связь с товарами
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
