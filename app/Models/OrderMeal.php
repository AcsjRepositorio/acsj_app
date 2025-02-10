<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderMeal extends Pivot
{
    use HasFactory;

    protected $table = 'order_meal';

    // Chave primária 'id' e autoincrement
    protected $primaryKey = 'id';
    public $incrementing = true;

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

    /**
     * Se as colunas 'disponivel_preparo' e 'entregue' forem TINYINT(1) ou booleans,
     * você pode declarar o cast para boolean:
     */
    protected $casts = [
        'disponivel_preparo' => 'boolean',
        'entregue'           => 'boolean',
    ];
}

