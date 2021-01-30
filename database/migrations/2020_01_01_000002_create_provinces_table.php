<?php

use App\Models\Province;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->integer('autonomous_community')->nullable(true);
            $table->string('postal_code', 2)->nullable(true);
            $table->timestamps();
        });

        Province::insert([
            ['id' => 1, 'name' => 'Álava', 'autonomous_community' => 17, 'postal_code' => '01', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Albacete', 'autonomous_community' => 6, 'postal_code' => '02', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Alicante', 'autonomous_community' => 10, 'postal_code' => '03', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Almería', 'autonomous_community' => 1, 'postal_code' => '04', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Ávila', 'autonomous_community' => 5, 'postal_code' => '05', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Badajoz', 'autonomous_community' => 11, 'postal_code' => '06', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'Baleares, Islas', 'autonomous_community' => 13, 'postal_code' => '07', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'Barcelona', 'autonomous_community' => 7, 'postal_code' => '08', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'name' => 'Burgos', 'autonomous_community' => 5, 'postal_code' => '09', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'name' => 'Cáceres', 'autonomous_community' => 11, 'postal_code' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'name' => 'Cádiz', 'autonomous_community' => 1, 'postal_code' => '11', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Castellón', 'autonomous_community' => 10, 'postal_code' => '12', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'name' => 'Ciudad Real', 'autonomous_community' => 6, 'postal_code' => '13', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'name' => 'Córdoba', 'autonomous_community' => 1, 'postal_code' => '14', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'name' => 'A Coruña', 'autonomous_community' => 12, 'postal_code' => '15', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'name' => 'Cuenca', 'autonomous_community' => 6, 'postal_code' => '16', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'name' => 'Girona', 'autonomous_community' => 7, 'postal_code' => '17', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'name' => 'Granada', 'autonomous_community' => 1, 'postal_code' => '18', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'name' => 'Guadalajara', 'autonomous_community' => 6, 'postal_code' => '19', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'name' => 'Guipuzcoa', 'autonomous_community' => 17, 'postal_code' => '20', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'name' => 'Huelva', 'autonomous_community' => 1, 'postal_code' => '21', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'name' => 'Huesca', 'autonomous_community' => 2, 'postal_code' => '22', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'name' => 'Jaén', 'autonomous_community' => 1, 'postal_code' => '23', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'name' => 'León', 'autonomous_community' => 5, 'postal_code' => '24', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'name' => 'Lleida', 'autonomous_community' => 7, 'postal_code' => '25', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'name' => 'La Rioja', 'autonomous_community' => 14, 'postal_code' => '26', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'name' => 'Lugo', 'autonomous_community' => 12, 'postal_code' => '27', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'name' => 'Madrid', 'autonomous_community' => 9, 'postal_code' => '28', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'name' => 'Málaga', 'autonomous_community' => 1, 'postal_code' => '29', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'name' => 'Murcia', 'autonomous_community' => 19, 'postal_code' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'name' => 'Navarra', 'autonomous_community' => 16, 'postal_code' => '31', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'name' => 'Orense', 'autonomous_community' => 12, 'postal_code' => '32', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'name' => 'Asturias', 'autonomous_community' => 18, 'postal_code' => '33', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'name' => 'Palencia', 'autonomous_community' => 5, 'postal_code' => '34', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'name' => 'Las Palmas', 'autonomous_community' => 3, 'postal_code' => '35', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'name' => 'Pontevedra', 'autonomous_community' => 12, 'postal_code' => '36', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'name' => 'Salamanca', 'autonomous_community' => 5, 'postal_code' => '37', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'name' => 'Santa Cruz de Tenerife', 'autonomous_community' => 3, 'postal_code' => '38', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'name' => 'Cantabria', 'autonomous_community' => 4, 'postal_code' => '39', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'name' => 'Segovia', 'autonomous_community' => 5, 'postal_code' => '40', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'name' => 'Sevilla', 'autonomous_community' => 1, 'postal_code' => '41', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'name' => 'Soria', 'autonomous_community' => 5, 'postal_code' => '42', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'name' => 'Tarragona', 'autonomous_community' => 7, 'postal_code' => '43', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'name' => 'Teruel', 'autonomous_community' => 2, 'postal_code' => '44', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'name' => 'Toledo', 'autonomous_community' => 6, 'postal_code' => '45', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 46, 'name' => 'Valencia', 'autonomous_community' => 10, 'postal_code' => '46', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'name' => 'Valladolid', 'autonomous_community' => 5, 'postal_code' => '47', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'name' => 'Vizcaya', 'autonomous_community' => 17, 'postal_code' => '48', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'name' => 'Zamora', 'autonomous_community' => 5, 'postal_code' => '49', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'name' => 'Zaragoza', 'autonomous_community' => 2, 'postal_code' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 51, 'name' => 'Ceuta', 'autonomous_community' => 8, 'postal_code' => '51', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'name' => 'Melilla', 'autonomous_community' => 15, 'postal_code' => '52', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'name' => 'Ninguna', 'autonomous_community' => 0, 'postal_code' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        //
    }
}
