<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'status',
        'customer_name',
        'customer_email',
        'payment_status',
        'user_id',
    ];

    /**
     * Relação com o usuário que fez o pedido.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação muitos-para-muitos com Meal.
     */
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'order_meal')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
