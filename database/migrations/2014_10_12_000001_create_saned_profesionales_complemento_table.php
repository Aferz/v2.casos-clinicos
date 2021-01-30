<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanedProfesionalesComplementoTable extends Migration
{
    public function up(): void
    {
        if (config('app.env') !== 'production') {
            Schema::connection('mysql-saned')->create(
                'profesionales_complemento',
                function (Blueprint $table) {
                    $table->increments('indice');
                    $table->integer('id_profesional')->nullable()->unique();
                    $table->string('prof_user_email')->nullable()->unique();
                    $table->string('prof_clave_md')->nullable();
                    $table->string('nombre_moodle')->nullable();
                    $table->string('apellidos_moodle')->nullable();
                    $table->string('nif_profesional')->nullable();
                    $table->string('nif_centro_trabajo')->nullable();
                    $table->integer('id_tipo_centro_trabajo')->nullable();
                    $table->integer('id_tipo_centro_trabajo_sis_salud')->nullable();
                    $table->integer('id_tipo_profesional')->nullable();
                    $table->integer('id_centro_trabajo')->nullable();
                    $table->integer('id_centro_trabajo_siap_centro_sis_salud')->nullable();
                    $table->string('id_centro_trabajo_ch_catalogo_sis_salud', 10)->nullable();
                    $table->string('txt_centro_trabajo_otros', 250)->nullable();
                    $table->string('txt_otras_especialidades', 250)->nullable();
                    $table->integer('id_prof_com_aut_sis_salud')->nullable();
                    $table->integer('id_prof_provincia_sis_salud')->nullable();
                    $table->integer('id_prof_municipio_sis_salud')->nullable();
                    $table->integer('id_part_com_aut_sis_salud')->nullable();
                    $table->integer('id_part_provincia_sis_salud')->nullable();
                    $table->integer('id_part_municipio_sis_salud')->nullable();
                    $table->string('cesion_datos', 2)->nullable();
                    $table->dateTime('ProfFechaInsert')->nullable();
                    $table->dateTime('ProfFechaUpdate')->nullable();
                }
            );
        }
    }

    public function down(): void
    {
        //
    }
}
