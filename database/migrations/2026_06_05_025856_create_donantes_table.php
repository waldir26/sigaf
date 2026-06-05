<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('donantes')) {
            Schema::create('donantes', function (Blueprint $table) {
                $table->id('id_donante');
                $table->string('nombre', 200);
                $table->string('telefono', 20)->nullable();
                $table->string('correo', 150)->nullable();
                $table->text('direccion')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('donantes');
    }
};