<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    public $timestamps = false; // Desativa timestamps automáticos
    protected $table = 'meals';

    protected $fillable = [
        'name',
        'description',
        'photo',
        'price',
        'menu_id',
        'category_id',
        'day_week_start',
        'day_of_week' ,
        'stock'
    ];

  
    

   

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'orders_meals')->withPivot('quantity');
    }

    public static function getAllMealsByDay()
    {
        $meals = self::all();
        return $meals->groupBy('day_of_week');
    }
}
