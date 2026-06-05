<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('gastos')) {
            Schema::create('gastos', function (Blueprint $table) {
                $table->id('id_gasto');
                $table->enum('categoria', ['Mantenimiento', 'Compras', 'Operacion', 'Programas']);
                $table->text('descripcion')->nullable();
                $table->decimal('monto', 12, 2);
                $table->date('fecha')->nullable();
                $table->string('comprobante', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('gastos');
    }
};