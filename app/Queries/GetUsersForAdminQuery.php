<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class GetUsersForAdminQuery
{
    use Concerns\Orderable;

    public function __construct(
        protected string $role,
        protected ?string $order = null
    ) {
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        $paginator = $this->query()
            ->paginate(perPage: $perPage, page: $page)
            ->onEachSide(1);

        if ($this->role !== 'all') {
            $paginator->appends([
                'role' => $this->role,
            ]);
        }

        return $paginator;
    }

    protected function query(): Builder
    {
        return User::query()
            ->select([
                'users.*',
                'countries.name AS country_name',
                'specialities.name AS speciality_name',
            ])
            ->leftJoin('countries', 'countries.id', 'users.country_id')
            ->leftJoin('specialities', 'specialities.id', 'users.speciality_id')
            ->when($this->role === 'doctor', fn ($q) => $this->applyRoleDoctorsToQuery($q))
            ->when($this->role === 'coordinator', fn ($q) => $this->applyRoleCoordinatorsToQuery($q))
            ->when($this->role === 'admin', fn ($q) => $this->applyRoleAdminsToQuery($q))
            ->orderBy($this->orderField(), $this->orderDirection());
    }

    protected function applyRoleDoctorsToQuery(Builder $query): Builder
    {
        return $query
            ->where('is_coordinator', false)
            ->where('is_admin', false);
    }

    protected function applyRoleCoordinatorsToQuery(Builder $query): Builder
    {
        return $query
            ->where('is_coordinator', true)
            ->where('is_admin', false);
    }

    protected function applyRoleAdminsToQuery(Builder $query): Builder
    {
        return $query
            ->where('is_coordinator', false)
            ->where('is_admin', true);
    }

    protected function allowedOrderFieldNames(): array
    {
        return [
            'name',
            'speciality' => 'speciality_name',
            'email',
        ];
    }
}
