<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('servicios_actividades')) {
            Schema::create('servicios_actividades', function (Blueprint $table) {
                $table->id('id_servicio');
                $table->string('tipo_servicio', 150);
                $table->text('descripcion')->nullable();
                $table->string('responsable', 150)->nullable();
                $table->date('fecha')->nullable();
                $table->decimal('monto', 12, 2);
                $table->string('comprobante', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('servicios_actividades');
    }
};