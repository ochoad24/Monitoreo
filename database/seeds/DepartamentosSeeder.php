<?php

use Illuminate\Database\Seeder;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('departamentos')->insert(
            array(
                array('departamento' => 'Alta Verapaz'),
                array('departamento' => 'Baja Verapaz'),
                array('departamento' => 'Chimaltenango'),
                array('departamento' => 'Chiquimula'),
                array('departamento' => 'El Progreso'),
                array('departamento' => 'Escuintla'),
                array('departamento' => 'Guatemala'),
                array('departamento' => 'Huehuetenango'),
                array('departamento' => 'Izabal'),
                array('departamento' => 'Jalapa'),
                array('departamento' => 'Jutiapa'),
                array('departamento' => 'Petén'),
                array('departamento' => 'Quetzaltenango'),
                array('departamento' => 'Quiché'),
                array('departamento' => 'Retalhuleu'),
                array('departamento' => 'Sacatepéquez'),
                array('departamento' => 'San Marcos'),
                array('departamento' => 'Santa Rosa'),
                array('departamento' => 'Sololá'),
                array('departamento' => 'Suchitepéquez'),
                array('departamento' => 'Totonicapán'),
                array('departamento' => 'Zacapa'),
        ));
    }
}
