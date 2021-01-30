<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use App\Models\User;
use App\Rules\PasswordRule;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class ResetPassword extends Action
{
    public function __invoke(
        SanedUserSynchronizer $synchronizer
    ): RedirectResponse {
        $data = $this->validate();
        $status = $this->resetPassword($data, $synchronizer);

        if ($status !== Password::PASSWORD_RESET) {
            return redirect()
                ->back()
                ->withInput(['email' => $data['email']])
                ->withErrors(['email' => __($status)]);
        }

        return redirect()
            ->route('login')
            ->with('passwordReset', true);
    }

    protected function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', new PasswordRule],
        ];
    }

    protected function resetPassword(
        array $data,
        SanedUserSynchronizer $synchronizer
    ): string {
        return Password::reset($data, function (User $user, string $password) use ($synchronizer) {
            $user->password = bcrypt($password);
            $user->save();

            $synchronizer->updateRemotePasswordUsingId($user->saned_user_id, $password);
        });
    }
}
