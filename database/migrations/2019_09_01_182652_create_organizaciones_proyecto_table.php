<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizacionesProyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizaciones_proyecto', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idproyecto')->unsigned();
            $table->foreign('idproyecto')->references('IdProyecto')->on('proyectos')->onDelete('cascade');
            $table->bigInteger('idorganizacion')->unsigned();
            $table->foreign('idorganizacion')->references('IdOrganizacion')->on('organizaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizaciones_proyecto');
    }
}
