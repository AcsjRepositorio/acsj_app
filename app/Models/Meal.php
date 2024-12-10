<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    protected $table='meals';

    protected $fillable=[

        'name',
        'desciption',
        'photo',
        'price',
        'menu_id',        
        'category_id',
    ];


    public function menu(){

        return $this->belongsTo(Menu::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_meals')->withPivot('quantity', 'day_of_week');
    }

}
