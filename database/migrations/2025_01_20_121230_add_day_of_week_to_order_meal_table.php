<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_meal', function (Blueprint $table) {
            $table->string('day_of_week')->nullable()->after('quantity');
        });
    }
    
};
