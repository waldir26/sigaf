<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('escuelas_beneficiarias')) {
            Schema::create('escuelas_beneficiarias', function (Blueprint $table) {
                $table->id('id_escuela');
                $table->string('nombre_escuela', 200);
                $table->string('director', 150)->nullable();
                $table->string('municipio', 100)->nullable();
                $table->integer('cantidad_estudiantes')->default(0);
                $table->unsignedBigInteger('id_programa')->nullable();
                $table->timestamps();
                
                $table->foreign('id_programa')->references('id_programa')->on('programas')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('escuelas_beneficiarias');
    }
};