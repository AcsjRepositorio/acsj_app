<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';

    protected $fillable=[

        'created_at',
        'total_amount',
        'delivery_status',
        'invoice_id',
        'reservation_code',
        'payment_status',
        'user_id'

    ];

     public function user(){

        return $this->belongsTo(User::class);
     }

     //Referencia a tabela pivot Orders_meals
     public function meals()
    {
        return $this->belongsToMany(Meal::class, 'orders_meals')->withPivot('quantity', 'day_of_week');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class);
    }
}
