<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    public ?int $userId = null;
    public string $role;
    public ?string $email = null;
    public ?string $name = null;
    public ?string $lastname1 = null;
    public ?string $lastname2 = null;
    public ?string $password = null;
    protected ?User $user = null;

    public function mount(): void
    {
        if ($this->user()) {
            $this->email = $this->user->email;
            $this->name = $this->user->name;
            $this->lastname1 = $this->user->lastname1;
            $this->lastname2 = $this->user->lastname2;
        }
    }

    public function render(): View
    {
        return view('livewire.user.form');
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|max:45',
            'lastname1' => 'required|max:45',
            'lastname2' => 'max:45',
        ];

        if (! $this->userId) {
            $rules['email'] = 'required|email|unique:users,email|max:250';
            $rules['password'] = ['required', new PasswordRule];
        } else {
            $rules['password'] = ['nullable', new PasswordRule];
        }

        return $rules;
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function save()
    {
        $validatedData = $this->validateForm();

        if (! $this->user()) {
            $this->createUser();
        } else {
            $this->updateUser($validatedData);
        }

        return redirect()->route('users.index', ['role' => $this->role]);
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function create()
    {
        return $this->save();
    }

    protected function user(): ?User
    {
        if (! $this->userId) {
            return null;
        }

        return $this->user = User::find($this->userId);
    }

    protected function createUser(): User
    {
        return $this->user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'lastname1' => $this->lastname1,
            'lastname2' => $this->lastname2,
            'password' => bcrypt($this->password),
            'is_coordinator' => $this->role === 'coordinator',
            'is_admin' => $this->role === 'admin',
        ]);
    }

    protected function updateUser(array $validatedData): User
    {
        foreach ($validatedData as $key => $value) {
            if ($key === 'password') {
                if ($value === null) {
                    continue;
                }

                $value = bcrypt($value);
            }

            $this->user->{$key} = $value;
        }

        return tap($this->user, fn () => $this->user->save());
    }

    protected function validateForm(): array
    {
        $this->resetValidation();

        try {
            return $this->validate();
        } catch (ValidationException $e) {
            $this->dispatchBrowserEvent('scrollIntoView', [
                'elementId' => $e->validator->errors()->keys()[0],
            ]);

            throw $e;
        }
    }
}
