<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('inventario')) {
            Schema::create('inventario', function (Blueprint $table) {
                $table->id('id_producto');
                $table->string('nombre_producto', 200);
                $table->string('categoria', 100)->nullable();
                $table->integer('cantidad')->default(0);
                $table->enum('estado', ['disponible', 'agotado', 'dado_baja'])->default('disponible');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('inventario');
    }
};