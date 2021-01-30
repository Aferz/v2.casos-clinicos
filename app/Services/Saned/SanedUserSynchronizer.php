<?php

namespace App\Services\Saned;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use stdClass;

class SanedUserSynchronizer
{
    public function findById(int $id): ?stdClass
    {
        return $this->findQuery()
            ->where('Profesionales.IdProfesional', $id)
            ->first();
    }

    public function findByEmail(string $email): ?stdClass
    {
        return $this->findQuery()
            ->where('Profesionales.ProfEmail', $email)
            ->first();
    }

    public function updateRemotePasswordUsingId(int $id, string $plainPassword): void
    {
        $this->table('Profesionales')
            ->where('IdProfesional', $id)
            ->update([
                'ProfClave' => $plainPassword,
            ]);

        $this->table('profesionales_complemento')
            ->where('id_profesional', $id)
            ->update([
                'prof_clave_md' => md5($plainPassword),
            ]);
    }

    public function syncFromLocalToRemote(User $user): int
    {
        if (!$user->saned_user_id) {
            return $this->createRemoteUser($user);
        }

        return $this->updateRemoteUser($user);
    }

    public function syncFromRemoteToLocalUsingId(int $id): ?User
    {
        if (!$remoteUser = $this->findById($id)) {
            return null;
        }

        return $this->syncFromRemoteToLocal($remoteUser);
    }

    public function syncFromRemoteToLocalUsingEmail(string $email): ?User
    {
        if (!$remoteUser = $this->findByEmail($email)) {
            return null;
        }

        return $this->syncFromRemoteToLocal($remoteUser);
    }

    public function syncFromRemoteToLocal(stdClass $remoteUser): User
    {
        $localUser = User::query()
            ->withTrashed()
            ->firstWhere('saned_user_id', $remoteUser->id);

        if (!$localUser) {
            $localUser = User::create([
                'email' => $remoteUser->email,
                'name' => $remoteUser->name,
                'password' => bcrypt($remoteUser->password),
                'saned_user_id' => $remoteUser->id,
            ]);
        }

        $localUser->update([
            'name' => $remoteUser->name,
            'lastname1' => $remoteUser->lastname1,
            'lastname2' => $remoteUser->lastname2,
            'password' => bcrypt($remoteUser->password),
            'phone' => $remoteUser->phone,
            'registration_number' => $remoteUser->registration_number,
            'country_id' => $remoteUser->country_id,
            'city' => $remoteUser->city,
            'speciality_id' => $remoteUser->speciality_id,
            'workcenter' => $remoteUser->workcenter,
            'registered_in_service' => $remoteUser->registered_in_service,
            'accepted_lab_mailing' => $remoteUser->accepted_lab_mailing,
        ]);

        return $localUser;
    }

    protected function findQuery(): Builder
    {
        return $this->table('Profesionales')
            ->select([
                'Profesionales.IdProfesional AS id',
                'Profesionales.ProfNombre AS name',
                'Profesionales.ProfEmail AS email',
                'Profesionales.ProfApellido1 AS lastname1',
                'Profesionales.ProfApellido2 AS lastname2',
                'Profesionales.ProfClave AS password',
                'Profesionales.ProfTlfMovil AS phone',
                'Profesionales.ProfNumColegiado AS registration_number',
                'Profesionales.ProfCtPais AS country_id',
                'Profesionales.ProfCtPoblacion AS city',
                'Prof-Esps.IdEspecialidad AS speciality_id',
                'profesionales_complemento.txt_centro_trabajo_otros AS workcenter',
                DB::raw('IFNULL(`Prof-Servicios`.EnvioMasivo, 0) AS accepted_lab_mailing'),
                DB::raw('IF(ISNULL(`Prof-Servicios`.IdProfesional), 0, 1) AS registered_in_service'),
            ])
            ->leftJoin('Prof-Esps', 'Prof-Esps.IdProfesional', 'Profesionales.IdProfesional')
            ->leftJoin('profesionales_complemento', 'profesionales_complemento.id_profesional', 'Profesionales.IdProfesional')
            ->leftJoin('Prof-Servicios', function (JoinClause $join) {
                $join
                    ->on('Prof-Servicios.IdProfesional', 'Profesionales.IdProfesional')
                    ->where('Prof-Servicios.IdServicio', config('cc.app.service_id'));
            })
            ->distinct('id');
    }

    protected function createRemoteUser(User $user): int
    {
        $this->table('Profesionales')
            ->insert([
                'ProfNombre' => $user->name,
                'ProfEmail' => $user->email,
                'ProfApellido1' => $user->lastname1,
                'ProfApellido2' => $user->lastname2,
                'ProfTlfMovil' => $user->phone,
                'ProfNumColegiado' => $user->registration_number,
                'ProfCtPais' => $user->country_id,
                'ProfCtPoblacion' => $user->city,
                'ProfFechaInsert' => now()->format('Y-m-d H:i:s'),
                'ProfFechaUpdate' => now()->format('Y-m-d H:i:s'),
            ]);

        $sanedUserId = $this->table('Profesionales')
            ->where('ProfEmail', $user->email)
            ->value('IdProfesional');

        $this->table('Prof-Esps')
            ->insert([
                'IdProfesional' => $sanedUserId,
                'IdEspecialidad' => $user->speciality_id,
            ]);

        $this->table('profesionales_complemento')
            ->insert([
                'id_profesional' => $sanedUserId,
                'txt_centro_trabajo_otros' => $user->workcenter,
                'prof_user_email' => $user->email,
            ]);

        $this->table('Prof-Servicios')
            ->insert([
                'IdProfesional' => $sanedUserId,
                'IdServicio' => config('cc.app.service_id'),
                'EnvioMasivo' => $user->accepted_lab_mailing,
            ]);

        return $sanedUserId;
    }

    protected function updateRemoteUser(User $user): int
    {
        $this->table('Profesionales')
            ->where('IdProfesional', $user->saned_user_id)
            ->update([
                'ProfNombre' => $user->name,
                'ProfApellido1' => $user->lastname1,
                'ProfApellido2' => $user->lastname2,
                'ProfTlfMovil' => $user->phone,
                'ProfNumColegiado' => $user->registration_number,
                'ProfCtPais' => $user->country_id,
                'ProfCtPoblacion' => $user->city,
                'ProfFechaUpdate' => now()->format('Y-m-d H:i:s'),
            ]);

        $this->table('Prof-Esps')
            ->where('IdProfesional', $user->saned_user_id)
            ->update([
                'IdEspecialidad' => $user->speciality_id,
            ]);

        $this->table('profesionales_complemento')
            ->where('id_profesional', $user->saned_user_id)
            ->update([
                'txt_centro_trabajo_otros' => $user->workcenter,
            ]);

        $this->table('Prof-Servicios')
            ->where('IdProfesional', $user->saned_user_id)
            ->where('IdServicio', config('cc.app.service_id'))
            ->update([
                'EnvioMasivo' => $user->accepted_lab_mailing,
            ]);

        $serviceExist = $this->table('Prof-Servicios')
            ->where('IdServicio', config('cc.app.service_id'))
            ->where('IdProfesional', $user->saned_user_id)
            ->exists();

        if (! $serviceExist) {
            $this->table('Prof-Servicios')
                ->insert([
                    'IdProfesional' => $user->saned_user_id,
                    'IdServicio' => config('cc.app.service_id'),
                    'EnvioMasivo' => $user->accepted_lab_mailing,
                ]);
        }

        return $user->saned_user_id;
    }

    protected function table(string $name): Builder
    {
        return DB::connection('mysql-saned')->table($name);
    }
}
