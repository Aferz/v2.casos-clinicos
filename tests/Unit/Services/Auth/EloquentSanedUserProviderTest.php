<?php

namespace Tests\Unit\Services\Auth;

use App\Models\User;
use App\Services\Auth\EloquentSanedUserProvider;
use App\Services\Saned\SanedUserSynchronizer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use stdClass;
use Tests\Unit\UnitTestCase;

class EloquentSanedUserProviderTest extends UnitTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->synchronizer = $this->mock(SanedUserSynchronizer::class);

        $this->app->instance(SanedUserSynchronizer::class, $this->synchronizer);
    }

    /** @test */
    public function it_synchronizes_data_from_remote_if_saned_user_is_found(): void
    {
        $sanedUser = new stdClass;
        $mockedUser = $this->mock(User::class);

        $this->synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn($sanedUser);

        $this->synchronizer
            ->shouldReceive('syncFromRemoteToLocal')
            ->with($sanedUser)
            ->andReturn($mockedUser);

        $mockedUser
            ->shouldReceive('restore');

        $user = $this->provider()->retrieveByCredentials([
            'email' => 'test@test.com',
        ]);

        $this->assertEquals($user, $mockedUser);
    }

    /** @test */
    public function it_returns_user_if_he_is_coordinator(): void
    {
        $this->synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(null);

        $coordinator = create(User::class, [
            'email' => 'test@test.com',
            'password' => 'password',
            'saned_user_id' => 1, // Avoid "afterCreate" factory's hook.
        ], ['coordinator']);

        $user = $this->provider()->retrieveByCredentials([
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $this->assertEquals($coordinator->id, $user->id);
    }

    /** @test */
    public function it_returns_user_if_he_is_admin(): void
    {
        $this->synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(null);

        $admin = create(User::class, [
            'email' => 'test@test.com',
            'password' => 'password',
            'saned_user_id' => 1, // Avoid "afterCreate" factory's hook.
        ], ['admin']);

        $user = $this->provider()->retrieveByCredentials([
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $this->assertEquals($admin->id, $user->id);
    }

    /** @test */
    public function it_deletes_the_user_from_local_database_if_user_is_not_coordinator_or_admin(): void
    {
        Carbon::setTestNow('2021-01-01 00:00:00');

        $this->synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(null);

        $doctor = create(User::class, [
            'email' => 'test@test.com',
            'password' => 'password',
            'saned_user_id' => 1, // Avoid "afterCreate" factory's hook.
        ]);

        $user = $this->provider()->retrieveByCredentials([
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $this->assertNull($user);

        $this->assertDatabaseHas('users', [
            'id' => $doctor->id,
            'deleted_at' => '2021-01-01 00:00:00',
        ]);
    }

    /** @test */
    public function it_returns_null_if_neither_user_and_saned_user_are_found(): void
    {
        $this->synchronizer
            ->shouldReceive('findByEmail')
            ->with('test@test.com')
            ->andReturn(null);

        $user = $this->provider()->retrieveByCredentials([
            'email' => 'test@test.com',
            'password' => '123XYZ',
            'saned_user_id' => 1, // Avoid "afterCreate" factory's hook.
        ]);

        $this->assertNull($user);
    }

    protected function provider(): EloquentSanedUserProvider
    {
        return app(EloquentSanedUserProvider::class, ['model' => User::class]);
    }
}
