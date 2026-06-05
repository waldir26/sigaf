<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('inscripciones')) {
            Schema::create('inscripciones', function (Blueprint $table) {
                $table->id('id_inscripcion');
                $table->unsignedBigInteger('id_participante');
                $table->unsignedBigInteger('id_programa');
                $table->date('fecha_inscripcion')->nullable();
                $table->enum('estado', ['activo', 'finalizado', 'cancelado'])->default('activo');
                $table->enum('tipo_inscripcion', ['escolar', 'sabatino', 'externo'])->default('escolar');
                $table->unsignedBigInteger('id_escuela')->nullable();
                $table->timestamps();
                
                $table->foreign('id_participante')->references('id_participante')->on('participantes')->onDelete('cascade');
                $table->foreign('id_programa')->references('id_programa')->on('programas')->onDelete('cascade');
                $table->foreign('id_escuela')->references('id_escuela')->on('escuelas_beneficiarias')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('inscripciones');
    }
};