<?php

namespace Tests\Feature\Unit\Livewire;

use App\Livewire\Auth\RegisterGuestForm;
use App\Models\Country;
use App\Models\Speciality;
use App\Models\User;
use App\Notifications\Auth\Registered;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use stdClass;
use Tests\Unit\UnitTestCase;

class RegisterGuestFormTest extends UnitTestCase
{
    use RefreshDatabase;

    /** @test */
    public function cant_register_user_if_email_exists_in_saned()
    {
        // We'll leave the Saned's user created.
        create(User::class, ['email' => 'test@test.com'])->delete();

        $this->app->instance(
            SanedUserSynchronizer::class,
            $synchronizer = $this->mock(SanedUserSynchronizer::class)
        );

        $synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(new stdClass);

        Livewire::test(RegisterGuestForm::class)
            ->set('email', 'test@test.com')
            ->set('password', 'Password1%')
            ->set('name', 'TestName')
            ->set('lastname1', 'TestLastName1')
            ->set('lastname2', 'TestLastName2')
            ->set('countryId', Country::first()->id)
            ->set('city', 'TestCity')
            ->set('phone', 'TestPhone')
            ->set('workcenter', 'TestWorkCenter')
            ->set('specialityId', Speciality::first()->id)
            ->set('sanedRules', true)
            ->set('labRules', true)
            ->call('register')
            ->assertHasErrors('email');

        $this->assertNull(User::where('email', 'test@test.com')->first());
    }

    /** @test */
    public function can_register_user()
    {
        Notification::fake();

        $this->app->instance(
            SanedUserSynchronizer::class,
            $synchronizer = $this->mock(SanedUserSynchronizer::class)
        );

        $synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(null);

        $synchronizer
            ->shouldReceive('syncFromLocalToRemote')
            ->withArgs(function ($user) {
                return $user->email === 'test@test.com';
            })
            ->andReturn(2000);

        $synchronizer
            ->shouldReceive('updateRemotePasswordUsingId')
            ->with(2000, 'Password1%');

        Livewire::test(RegisterGuestForm::class)
            ->set('email', 'test@test.com')
            ->set('password', 'Password1%')
            ->set('name', 'TestName')
            ->set('lastname1', 'TestLastName1')
            ->set('lastname2', 'TestLastName2')
            ->set('countryId', Country::first()->id)
            ->set('city', 'TestCity')
            ->set('phone', 'TestPhone')
            ->set('workcenter', 'TestWorkCenter')
            ->set('specialityId', Speciality::first()->id)
            ->set('sanedRules', true)
            ->set('labRules', true)
            ->call('register')
            ->assertRedirect(route('directory'));

        Notification::assertSentTo(
            [User::firstWhere('email', 'test@test.com')],
            Registered::class
        );

        $this->assertDatabaseHas('users', [
            'saned_user_id' => 2000,
            'email' => 'test@test.com',
            'name' => 'TestName',
            'lastname1' => 'TestLastName1',
            'lastname2' => 'TestLastName2',
            'phone' => 'TestPhone',
            'speciality_id' => Speciality::first()->id,
            'country_id' => Country::first()->id,
            'city' => 'TestCity',
            'workcenter' => 'TestWorkCenter',
            'registered_in_service' => true,
            'is_admin' => false,
            'is_coordinator' => false,
            'accepted_lab_mailing' => false,
            'accepted_saned_mailing' => false,
        ]);

        $this->assertTrue(auth()->user()->email === 'test@test.com');
        $this->assertTrue(Hash::check('Password1%', auth()->user()->password));
    }
}
