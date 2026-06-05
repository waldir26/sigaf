<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('programas')) {
            Schema::create('programas', function (Blueprint $table) {
                $table->id('id_programa');
                $table->string('nombre', 150);
                $table->text('descripcion')->nullable();
                $table->date('fecha_inicio')->nullable();
                $table->date('fecha_fin')->nullable();
                $table->enum('estado', ['activo', 'inactivo', 'finalizado'])->default('activo');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('programas');
    }
};