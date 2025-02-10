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
        'payment_method',
        'transaction_id',
    ];

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'order_meal')
                    ->using(OrderMeal::class) // Diz que a pivot é OrderMeal
                    ->withPivot([
                        'id', // crucial pra poder atualizar via find(id)
                        'quantity',
                        'day_of_week',
                        'pickup_time',
                        'note',
                        'disponivel_preparo',
                        'entregue',
                    ])
                    ->withTimestamps();
    }
}
