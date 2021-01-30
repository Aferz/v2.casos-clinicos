<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\User;
use App\Queries\GetUsersForAdminQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowUsersPage extends Action
{
    public function __invoke()
    {
        $this->authorizeUserTo('list', User::class);

        return view('web.users.index-admins', [
            'role' => $this->role(),
            'users' => $this->getUsers(),
        ]);
    }

    protected function getUsers(): LengthAwarePaginator
    {
        return (new GetUsersForAdminQuery(
            $this->role(),
            $this->order(),
        ))->paginate($this->page());
    }

    protected function role(): string
    {
        return $this->request->get('role', 'doctor');
    }

    protected function page(): string
    {
        return $this->request->get('page', 1);
    }

    protected function order(): ?string
    {
        return $this->request->get('order');
    }
}
