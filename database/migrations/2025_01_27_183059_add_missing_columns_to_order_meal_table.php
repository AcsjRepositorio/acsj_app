<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrações.
     */
    public function up()
    {
        Schema::table('order_meal', function (Blueprint $table) {
            // Adiciona 'day_of_week' se não existir
            if (!Schema::hasColumn('order_meal', 'day_of_week')) {
                $table->string('day_of_week')->nullable()->after('quantity');
            }

            // Adiciona 'pickup_time' se não existir
            if (!Schema::hasColumn('order_meal', 'pickup_time')) {
                $table->string('pickup_time')->nullable()->after('day_of_week');
            }

            // Adiciona 'note' se não existir
            if (!Schema::hasColumn('order_meal', 'note')) {
                $table->text('note')->nullable()->after('pickup_time');
            }

            // Adiciona 'disponivel_preparo' se não existir
            if (!Schema::hasColumn('order_meal', 'disponivel_preparo')) {
                $table->boolean('disponivel_preparo')->default(false)->after('note');
            }

            // Adiciona 'entregue' se não existir
            if (!Schema::hasColumn('order_meal', 'entregue')) {
                $table->boolean('entregue')->default(false)->after('disponivel_preparo');
            }
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down()
    {
        Schema::table('order_meal', function (Blueprint $table) {
            // Remove as colunas somente se existirem
            if (Schema::hasColumn('order_meal', 'entregue')) {
                $table->dropColumn('entregue');
            }
            if (Schema::hasColumn('order_meal', 'disponivel_preparo')) {
                $table->dropColumn('disponivel_preparo');
            }
            if (Schema::hasColumn('order_meal', 'note')) {
                $table->dropColumn('note');
            }
            if (Schema::hasColumn('order_meal', 'pickup_time')) {
                $table->dropColumn('pickup_time');
            }
            if (Schema::hasColumn('order_meal', 'day_of_week')) {
                $table->dropColumn('day_of_week');
            }
        });
    }
};
