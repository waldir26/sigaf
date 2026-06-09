<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ventas_bienes')) {
            Schema::create('ventas_bienes', function (Blueprint $table) {
                $table->id('id_venta');
                $table->string('articulo', 200);
                $table->decimal('monto', 12, 2);
                $table->date('fecha')->nullable();
                $table->string('comprobante', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ventas_bienes');
    }
};