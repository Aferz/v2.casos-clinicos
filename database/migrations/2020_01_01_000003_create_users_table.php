<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saned_user_id')->nullable();
            $table->string('name', 255);
            $table->string('lastname1', 255)->nullable();
            $table->string('lastname2', 255)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('registration_number', 45)->nullable();
            $table->foreignId('speciality_id')->nullable();
            $table->foreignId('country_id')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('workcenter', 255)->nullable();
            $table->string('avatar_path')->nullable();
            $table->boolean('registered_in_service')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_coordinator')->default(false);
            $table->boolean('accepted_lab_mailing')->default(false);
            $table->boolean('accepted_saned_mailing')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('speciality_id')
                ->references('id')
                ->on('specialities');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries');
        });

        User::create([
            'name' => 'Admin',
            'lastname1' => 'Admin',
            'email' => 'admin@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Coordinador',
            'lastname1' => '1',
            'email' => 'coordinator@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_coordinator' => true,
        ]);

        User::create([
            'name' => 'Coordinador',
            'lastname1' => '2',
            'email' => 'coordinator2@casosclinicos.com',
            'password' => bcrypt('Password1%'),
            'is_coordinator' => true,
        ]);
    }

    public function down(): void
    {
        //
    }
}
