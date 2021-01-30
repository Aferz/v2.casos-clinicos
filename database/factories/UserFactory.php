<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Speciality;
use App\Models\User;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'saned_user_id' => null,
            'name' => $this->faker->firstName,
            'lastname1' => $this->faker->lastName,
            'lastname2' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$vJIrMdhKb0aVrVkU6lwWdeAUqNG1.Oxh3dVXoiWHaxXKUFY0xlC.2', // Password1%
            'phone' => $this->faker->phoneNumber,
            'registration_number' => random_int(100_000_000, 999_999_999),
            'speciality_id' => fn () => Speciality::all()->random(),
            'country_id' => fn () => Country::all()->random(),
            'city' => $this->faker->city,
            'workcenter' => $this->faker->city,
            'avatar_path' => null,
            'is_admin' => false,
            'is_coordinator' => false,
            'accepted_lab_mailing' => false,
            'accepted_saned_mailing' => false,
            'registered_in_service' => true,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
                'is_coordinator' => false,
            ];
        });
    }

    public function coordinator()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => false,
                'is_coordinator' => true,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if ($user->saned_user_id) {
                return;
            }

            $synchronizer = app(SanedUserSynchronizer::class);
            $sanedUserId = $synchronizer->syncFromLocalToRemote($user);
            $synchronizer->updateRemotePasswordUsingId($sanedUserId, 'Password1%');

            $user->saned_user_id = $sanedUserId;
            $user->save();
        });
    }
}
