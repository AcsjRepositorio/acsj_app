<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table='invoices';

    protected $fillable=[
        'invoice_number',
        'created_at',
        'order_id'
        

    ];


    public function order(){
        return $this->belongsTo(Order::class);
    }
}
