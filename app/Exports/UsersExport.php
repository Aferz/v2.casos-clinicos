<?php

namespace App\Exports;

use App\Models\User;
use App\Queries\GetUsersForAdminQuery;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $role,
    ) {
    }

    public function headings(): array
    {
        return [
            __('Name'),
            __('Lastname 1'),
            __('Lastname 2'),
            __('Email'),
            __('Phone'),
            __('Registration number'),
            __('Speciality'),
            __('Country'),
            __('City'),
            __('Workcenter'),
            __('Saned\'s advertising'),
            __('Client\'s advertising'),
        ];
    }

    public function collection()
    {
        return $this->getUsers()
            ->map(function (User $user) {
                return [
                    $user->name,
                    $user->lastname1 ?? '—',
                    $user->lastname2 ?? '—',
                    $user->email,
                    $user->phone ?? '—',
                    $user->registration_number ?? '—',
                    $user->speciality_name ?? '—',
                    $user->country_name ?? '—',
                    $user->city ?? '—',
                    $user->workcenter ?? '—',
                    $user->accepted_saned_mailing ? __('Yes') : __('No'),
                    $user->accepted_lab_mailing ? __('Yes') : __('No'),
                ];
            });
    }

    protected function getUsers(): Collection
    {
        return (new GetUsersForAdminQuery(
            $this->role,
        ))->get();
    }
}
