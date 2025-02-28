<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Adicionamos "Bebidas" à lista de valores do enum
            $table->enum('meal_category', [
                'Pequeno almoço', 
                'Almoço', 
                'Jantar', 
                'Lanche', 
                'Bebidas'
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Na reversão, voltamos para os valores anteriores
            $table->enum('meal_category', [
                'Pequeno almoço', 
                'Almoço', 
                'Jantar', 
                'Lanche'
            ])->change();
        });
    }
};
