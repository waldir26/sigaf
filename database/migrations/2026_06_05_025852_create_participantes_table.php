<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('participantes')) {
            Schema::create('participantes', function (Blueprint $table) {
                $table->id('id_participante');
                $table->string('nombres', 150);
                $table->string('apellidos', 150);
                $table->integer('edad')->nullable();
                $table->string('telefono', 20)->nullable();
                $table->string('correo', 150)->nullable();
                $table->text('direccion')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
};