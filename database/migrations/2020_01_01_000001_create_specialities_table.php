<?php

use App\Models\Speciality;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialitiesTable extends Migration
{
    public function up(): void
    {
        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
        });

        Speciality::insert([
            ['id' => 3, 'name' => 'Anestesiología'],
            ['id' => 4, 'name' => 'Medicina Familiar y Comunitaria'],
            ['id' => 12, 'name' => 'Endocrinología'],
            ['id' => 21, 'name' => 'Medicina Interna'],
            ['id' => 22, 'name' => 'Medicina Preventiva y Salud Pública'],
            ['id' => 25, 'name' => 'Neurocirugía'],
            ['id' => 26, 'name' => 'Neurología'],
            ['id' => 29, 'name' => 'Oncología Médica'],
            ['id' => 33, 'name' => 'Reumatología'],
            ['id' => 37, 'name' => 'Cirugía Ortopédica y Traumatología'],
            ['id' => 43, 'name' => 'Medicina General'],
        ]);
    }

    public function down(): void
    {
        //
    }
}
