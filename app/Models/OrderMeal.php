<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderMeal extends Pivot
{
    use HasFactory;

    protected $table = 'order_meal';

    protected $fillable = [
        'quantity',
        'day_of_week',
        'pickup_time',
        'note',
        'disponivel_preparo',
        'entregue',
        'order_id',
        'meal_id',
    ];
}
