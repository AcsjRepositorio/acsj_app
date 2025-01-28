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
            $table->string('pickup_time')->nullable()->after('quantity');
            $table->text('note')->nullable()->after('pickup_time');
            $table->boolean('disponivel_preparo')->default(false)->after('note');
            $table->boolean('entregue')->default(false)->after('disponivel_preparo');
        });
    }

    /**
     * Reverte as migrações.
     */
    public function down()
    {
        Schema::table('order_meal', function (Blueprint $table) {
            $table->dropColumn(['pickup_time', 'note', 'disponivel_preparo', 'entregue']);
        });
    }
};
