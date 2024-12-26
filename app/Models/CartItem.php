<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'meal_id', 'quantity', 'day_of_week'];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}

