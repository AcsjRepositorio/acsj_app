<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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
    
    /**
     * Boot function for using with model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Gera automaticamente um order_id (caso não seja setado)
        static::creating(function ($model) {
            if (empty($model->order_id)) {
                $model->order_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relação com o usuário que fez o pedido (opcional).
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
                    ->withPivot([
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
