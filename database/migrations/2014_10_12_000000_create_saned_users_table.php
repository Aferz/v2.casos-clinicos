<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanedUsersTable extends Migration
{
    public function up(): void
    {
        if (config('app.env') !== 'production') {
            Schema::connection('mysql-saned')->create(
                'Profesionales',
                function (Blueprint $table) {
                    $table->increments('IdProfesional');
                    $table->string('ProfNombre', 45)->nullable();
                    $table->string('ProfApellido1', 45)->nullable();
                    $table->string('ProfApellido2', 45)->nullable();
                    $table->string('ProfNumColegiado', 45)->nullable();
                    $table->string('ProfTlfFijo', 20)->nullable();
                    $table->string('ProfTlfMovil', 20)->nullable();
                    $table->string('ProfEmail', 250)->nullable();
                    $table->string('ProfEmail2', 250)->nullable();
                    $table->enum('ProfGenero', ['M', 'F'])->nullable();
                    $table->tinyInteger('ProfTipoProfesional')->nullable();
                    $table->string('ProfPartDireccion', 200)->nullable();
                    $table->string('ProfPartPoblacion', 45)->nullable();
                    $table->unsignedSmallInteger('ProfPartProvincia')->default(54);
                    $table->string('ProfPartCp', 45)->nullable();
                    $table->smallInteger('ProfPartPais')->default(300)->nullable();
                    $table->string('ProfCtDireccion', 200)->nullable();
                    $table->integer('ProfCtIDPoblacion')->nullable();
                    $table->string('ProfCtPoblacion', 45)->nullable();
                    $table->smallInteger('ProfCtProvincia')->default(54);
                    $table->string('ProfCtCp', 45)->nullable();
                    $table->unsignedSmallInteger('ProfCtPais')->default(300);
                    $table->string('ProfBonificadoPor', 255)->nullable();
                    $table->string('ProfUsuario', 45)->nullable();
                    $table->string('ProfClave', 45)->nullable();
                    $table->tinyInteger('ProfActivo')->default(true);
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
