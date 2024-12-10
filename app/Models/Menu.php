<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    protected $table='menus';

    protected $fillable=[

        "week_start_date",
        "week_end_date", 
    ];

    public function meals(){

        return $this->hasMany(Meal::class);
    }
    
}
