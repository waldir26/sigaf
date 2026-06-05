<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('movimientos_financieros')) {
            Schema::create('movimientos_financieros', function (Blueprint $table) {
                $table->id('id_movimiento');
                $table->enum('tipo', ['Ingreso', 'Gasto']);
                $table->enum('origen', ['Donacion', 'Servicio', 'Venta', 'Mantenimiento', 'Compras', 'Operacion', 'Programas']);
                $table->decimal('monto', 12, 2);
                $table->date('fecha');
                $table->text('descripcion')->nullable();
                $table->string('tabla_referencia', 50)->nullable();
                $table->unsignedBigInteger('id_referencia')->nullable();
                $table->timestamps();
                
                $table->index('fecha');
                $table->index('tipo');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('movimientos_financieros');
    }
};