<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->string('nombre', 100);
                $table->string('apellido', 100);
                $table->string('correo', 150)->unique();
                $table->string('usuario', 50)->unique();
                $table->string('contrasena', 255);
                $table->enum('rol', ['admin', 'empleado'])->default('empleado');
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};