<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
           $table->mediumText('name');
           $table->longText('description');
           $table->string('photo')->nullable();
           $table->decimal('price',8,2);
           $table->unsignedBigInteger('menu_id');
           $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
