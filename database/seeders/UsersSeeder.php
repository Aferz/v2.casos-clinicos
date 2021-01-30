<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $user = create(User::class, ['email' => 'doctor@casosclinicos.com']);
        $users = create(User::class, [], [], 30);
    }
}
