<?php

namespace App\Http\Actions\Web;

use App\Exports\UsersExport;
use App\Http\Actions\Action;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportUsersList extends Action
{
    public function __invoke(): BinaryFileResponse
    {
        $this->authorizeUserTo('exportList', User::class);

        $data = $this->validate();

        $exporter = new UsersExport(
            $data['role'],
        );

        return Excel::download(
            $exporter,
            str_replace('-', '_', $data['role']) . '_users.' . $data['as']
        );
    }

    protected function rules(): array
    {
        return [
            'as' => 'required|in:xlsx,csv',
            'role' => 'required|in:doctor,coordinator,admin',
        ];
    }
}
