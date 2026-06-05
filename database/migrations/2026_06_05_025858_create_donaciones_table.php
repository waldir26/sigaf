<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('donaciones')) {
            Schema::create('donaciones', function (Blueprint $table) {
                $table->id('id_donacion');
                $table->unsignedBigInteger('id_donante');
                $table->enum('tipo_donacion', ['monetaria', 'especie']);
                $table->decimal('monto', 12, 2)->default(0);
                $table->text('descripcion')->nullable();
                $table->date('fecha')->nullable();
                $table->string('comprobante', 255)->nullable();
                $table->timestamps();
                
                $table->foreign('id_donante')->references('id_donante')->on('donantes')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('donaciones');
    }
};