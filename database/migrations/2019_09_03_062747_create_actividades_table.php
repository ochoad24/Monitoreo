<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('actividad',500);
            $table->integer('tareas');
            $table->integer('tareasCompletadas');
            $table->integer('tareasPendientes');
            $table->date('fechaInicio');
            $table->date('fechaFinal');
            $table->tinyInteger('estado');
            $table->bigInteger('idProyecto')->unsigned();
            $table->foreign('idProyecto')->references('IdProyecto')->on('proyectos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}
