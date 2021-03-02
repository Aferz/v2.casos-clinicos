<?php

namespace Tests\Unit\Services\Saned;

use App\Models\Country;
use App\Models\Speciality;
use App\Models\User;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Unit\UnitTestCase;

class SanedUserSynchronizerTest extends UnitTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider findDataProvider
     */
    public function find(string $method, $id): void
    {
        $this->createRemoteUser();

        $sanedUser = app(SanedUserSynchronizer::class)->{$method}($id);

        $this->assertEquals($sanedUser->id, 1);
        $this->assertEquals($sanedUser->name, 'name');
        $this->assertEquals($sanedUser->email, 'email@email.com');
        $this->assertEquals($sanedUser->lastname1, 'lastname1');
        $this->assertEquals($sanedUser->lastname2, 'lastname2');
        $this->assertEquals($sanedUser->password, 'password');
        $this->assertEquals($sanedUser->phone, 'phone');
        $this->assertEquals($sanedUser->registration_number, 'registration_number');
        $this->assertEquals($sanedUser->country_id, Country::first()->id);
        $this->assertEquals($sanedUser->city, 'city');
        $this->assertEquals($sanedUser->speciality_id, Speciality::first()->id);
        $this->assertEquals($sanedUser->workcenter, 'workcenter');
        $this->assertEquals($sanedUser->accepted_lab_mailing, 1);
        $this->assertEquals($sanedUser->registered_in_service, 1);
    }

    /** @test */
    public function find_user_not_registered_in_service(): void
    {
        DB::connection('mysql-saned')
            ->table('Profesionales')
            ->insert([
                'IdProfesional' => 1,
            ]);

        $sanedUser = app(SanedUserSynchronizer::class)->findById(1);

        $this->assertEquals($sanedUser->registered_in_service, 0);
    }

    /** @test */
    public function find_user_without_accepted_lab_mailing(): void
    {
        DB::connection('mysql-saned')
            ->table('Profesionales')
            ->insert([
                'IdProfesional' => 1,
            ]);

        DB::connection('mysql-saned')
            ->table('Prof-Servicios')
            ->insert([
                'IdProfesional' => 1,
                'IdServicio' => config('cc.app.service_id'),
                'EnvioMasivo' => 0,
            ]);

        $sanedUser = app(SanedUserSynchronizer::class)->findById(1);

        $this->assertEquals($sanedUser->registered_in_service, 1);
        $this->assertEquals($sanedUser->accepted_lab_mailing, 0);
    }

    /**
     * @test
     * @dataProvider synchronizeFromRemoteToLocalDataProvider
     **/
    public function synchronize_from_remote_to_local_create(string $method, $id): void
    {
        $this->createRemoteUser();

        app(SanedUserSynchronizer::class)->{$method}($id);

        $this->assertDatabaseHas('users', [
            'saned_user_id' => 1,
            'email' => 'email@email.com',
            'name' => 'name',
            'lastname1' => 'lastname1',
            'lastname2' => 'lastname2',
            'phone' => 'phone',
            'registration_number' => 'registration_number',
            'country_id' => Country::first()->id,
            'city' => 'city',
            'speciality_id' => Speciality::first()->id,
            'workcenter' => 'workcenter',
            'accepted_lab_mailing' => 1,
            'registered_in_service' => 1,
        ]);

        $user = User::firstWhere('email', 'email@email.com');
        $this->assertTrue(Hash::check('password', $user->password));
    }

    /**
     * @test
     * @dataProvider synchronizeFromRemoteToLocalDataProvider
     **/
    public function synchronize_from_remote_to_local_update(string $method, $id): void
    {
        $this->createRemoteUser();

        $user = create(User::class, [
            'saned_user_id' => 1,
            'email' => 'email@email.com',
            'password' => bcrypt('test'),
            'name' => 'test',
            'lastname1' => 'test',
            'lastname2' => 'test',
            'phone' => 'test',
            'registration_number' => 'test',
            'country_id' => Country::first()->id,
            'city' => 'test',
            'speciality_id' => Speciality::first()->id,
            'workcenter' => 'test',
            'accepted_lab_mailing' => 0,
            'registered_in_service' => 0,
            'deleted_at' => '2020-01-01 00:00:00',
        ]);

        app(SanedUserSynchronizer::class)->{$method}($id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'email@email.com',
            'saned_user_id' => 1,
            'name' => 'name',
            'lastname1' => 'lastname1',
            'lastname2' => 'lastname2',
            'phone' => 'phone',
            'registration_number' => 'registration_number',
            'country_id' => Country::first()->id,
            'city' => 'city',
            'speciality_id' => Speciality::first()->id,
            'workcenter' => 'workcenter',
            'accepted_lab_mailing' => 1,
            'registered_in_service' => 1,
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    /** @test */
    public function synchronize_from_local_to_remote_create(): void
    {
        $user = create(User::class, ['saned_user_id' => 1]);
        $user->update(['saned_user_id' => null]);

        $sanedUserId = app(SanedUserSynchronizer::class)->syncFromLocalToRemote($user);

        $this->assertDatabaseHas('Profesionales', [
            'IdProfesional' => $sanedUserId,
            'ProfNombre' => $user->name,
            'ProfEmail' => $user->email,
            'ProfApellido1' => $user->lastname1,
            'ProfApellido2' => $user->lastname2,
            'ProfTlfMovil' => $user->phone,
            'ProfNumColegiado' => $user->registration_number,
            'ProfCtPais' => $user->country_id,
            'ProfCtPoblacion' => $user->city,
        ], 'mysql-saned');

        $this->assertDatabaseHas('Prof-Esps', [
            'IdProfesional' => $sanedUserId,
            'IdEspecialidad' => $user->speciality_id,
        ], 'mysql-saned');

        $this->assertDatabaseHas('profesionales_complemento', [
            'id_profesional' => $sanedUserId,
            'txt_centro_trabajo_otros' => $user->workcenter,
            'prof_user_email' => $user->email,
        ], 'mysql-saned');

        $this->assertDatabaseHas('Prof-Servicios', [
            'IdProfesional' => $sanedUserId,
            'IdServicio' => config('cc.app.service_id'),
            'EnvioMasivo' => $user->accepted_lab_mailing,
        ], 'mysql-saned');
    }

    /** @test */
    public function synchronize_from_local_to_remote_update(): void
    {
        $user = create(User::class);

        $user->update([
            'name' => 'test',
            'lastname1' => 'test',
            'lastname2' => 'test',
            'phone' => 'test',
            'registration_number' => 'test',
            'country_id' => Country::first()->id,
            'city' => 'test',
            'speciality_id' => Speciality::first()->id,
            'workcenter' => 'test',
            'accepted_lab_mailing' => 1,
        ]);

        $sanedUserId = app(SanedUserSynchronizer::class)->syncFromLocalToRemote($user);

        $this->assertDatabaseHas('Profesionales', [
            'IdProfesional' => $sanedUserId,
            'ProfEmail' => $user->email,
            'ProfNombre' => 'test',
            'ProfApellido1' => 'test',
            'ProfApellido2' => 'test',
            'ProfTlfMovil' => 'test',
            'ProfNumColegiado' => 'test',
            'ProfCtPais' => Country::first()->id,
            'ProfCtPoblacion' => 'test',
        ], 'mysql-saned');

        $this->assertDatabaseHas('Prof-Esps', [
            'IdProfesional' => $sanedUserId,
            'IdEspecialidad' => Speciality::first()->id,
        ], 'mysql-saned');

        $this->assertDatabaseHas('profesionales_complemento', [
            'id_profesional' => $sanedUserId,
            'txt_centro_trabajo_otros' => 'test',
        ], 'mysql-saned');

        $this->assertDatabaseHas('Prof-Servicios', [
            'IdProfesional' => $sanedUserId,
            'IdServicio' => config('cc.app.service_id'),
            'EnvioMasivo' => 1,
        ], 'mysql-saned');
    }

    /**
     * @test
     * @dataProvider updateRemotePasswordDataProvider
     **/
    public function update_remote_password(string $method, $id): void
    {
        $this->createRemoteUser();

        app(SanedUserSynchronizer::class)->{$method}($id, 'plainpassword');

        $this->assertDatabaseHas('Profesionales', [
            'IdProfesional' => 1,
            'ProfClave' => 'plainpassword',
        ], 'mysql-saned');

        $this->assertDatabaseHas('profesionales_complemento', [
            'id_profesional' => 1,
            'prof_clave_md' => md5('plainpassword'),
        ], 'mysql-saned');
    }

    public function findDataProvider(): array
    {
        return [
            ['findById', 1],
            ['findByEmail', 'email@email.com'],
        ];
    }

    public function synchronizeFromRemoteToLocalDataProvider(): array
    {
        return [
            ['syncFromRemoteToLocalUsingId', 1],
            ['syncFromRemoteToLocalUsingEmail', 'email@email.com'],
        ];
    }

    public function updateRemotePasswordDataProvider(): array
    {
        return [
            ['updateRemotePasswordUsingId', 1],
        ];
    }

    protected function createRemoteUser(): void
    {
        DB::connection('mysql-saned')
            ->table('Profesionales')
            ->insert([
                'IdProfesional' => 1,
                'ProfNombre' => 'name',
                'ProfEmail' => 'email@email.com',
                'ProfClave' => 'password',
                'ProfApellido1' => 'lastname1',
                'ProfApellido2' => 'lastname2',
                'ProfTlfMovil' => 'phone',
                'ProfNumColegiado' => 'registration_number',
                'ProfCtPais' => Country::first()->id,
                'ProfCtPoblacion' => 'city',
            ]);

        DB::connection('mysql-saned')
            ->table('Prof-Esps')
            ->insert([
                'IdProfesional' => 1,
                'IdEspecialidad' => Speciality::first()->id,
            ]);

        DB::connection('mysql-saned')
            ->table('profesionales_complemento')
            ->insert([
                'id_profesional' => 1,
                'txt_centro_trabajo_otros' => 'workcenter',
            ]);

        DB::connection('mysql-saned')
            ->table('Prof-Servicios')
            ->insert([
                'IdProfesional' => 1,
                'IdServicio' => config('cc.app.service_id'),
                'EnvioMasivo' => 1,
            ]);
    }
}
