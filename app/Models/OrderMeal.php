<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderMeal extends Pivot
{
    use HasFactory;

    protected $table = 'order_meal';

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

    protected $casts = [
        'disponivel_preparo' => 'boolean',
        'entregue'           => 'boolean',
    ];

    /**
     * Relacionamento com o Model Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Relacionamento com o Model Meal
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
}
