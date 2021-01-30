<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanedServicesTable extends Migration
{
    public function up(): void
    {
        if (config('app.env') !== 'production') {
            Schema::connection('mysql-saned')->create(
                'Prof-Servicios',
                function (Blueprint $table) {
                    $table->increments('IdProf-Servicios');
                    $table->integer('IdProfesional')->nullable();
                    $table->integer('IdServicio')->nullable();
                    $table->enum('TipoSuscripcion', ['Gratuita', 'Pago', 'Bonificada', 'Fija'])->nullable();
                    $table->integer('EnvioMasivo')->nullable();
                }
            );
        }
    }

    public function down(): void
    {
        //
    }
}
