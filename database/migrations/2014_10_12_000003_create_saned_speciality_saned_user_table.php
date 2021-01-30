<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanedSpecialitySanedUserTable extends Migration
{
    public function up(): void
    {
        if (config('app.env') !== 'production') {
            Schema::connection('mysql-saned')->create(
                'Prof-Esps',
                function (Blueprint $table) {
                    $table->increments('IdProf-Esp');
                    $table->integer('IdProfesional')->nullable();
                    $table->integer('IdEspecialidad')->nullable();
                }
            );
        }
    }

    public function down(): void
    {
        //
    }
}
