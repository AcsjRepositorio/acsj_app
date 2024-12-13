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
        'day_of_week',       // Para armazenar o nome do dia da semana
        'day_week_start',    // Adicione este campo se ele for usado para armazenar a data
    ];

    protected $casts = [
        'day_week_start' => 'date', // Certifica-se de que 'day_week_start' é tratado como uma data
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
}
