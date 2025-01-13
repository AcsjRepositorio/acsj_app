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
        Schema::create('order_meal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('meal_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('meal_id')->references('id')->on('meals')->onDelete('cascade');

            // Evitar duplicatas
            $table->unique(['order_id', 'meal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_meal');
    }
};
