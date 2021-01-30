<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
        });

        Country::insert([
            ['id' => 33, 'name' => 'Brasil'],
            ['id' => 46, 'name' => 'Chile'],
            ['id' => 52, 'name' => 'Colombia'],
            ['id' => 60, 'name' => 'Costa Rica'],
            ['id' => 65, 'name' => 'República Dominicana'],
            ['id' => 66, 'name' => 'Ecuador'],
            ['id' => 68, 'name' => 'El Salvador'],
            ['id' => 94, 'name' => 'Guatemala'],
            ['id' => 102, 'name' => 'Honduras'],
            ['id' => 146, 'name' => 'México'],
            ['id' => 157, 'name' => 'Nicaragua'],
            ['id' => 170, 'name' => 'Panamá'],
            ['id' => 173, 'name' => 'Perú'],
        ]);
    }

    public function down(): void
    {
        //
    }
}
