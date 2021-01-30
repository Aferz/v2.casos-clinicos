<?php

namespace App\Livewire\Auth;

use App\Models\Country;
use App\Models\Speciality;
use App\Models\User;
use App\Notifications\Auth\Registered;
use App\Rules\PasswordRule;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class RegisterGuestForm extends Component
{
    public string $email = '';
    public string $password = '';
    public string $name = '';
    public string $lastname1 = '';
    public string $lastname2 = '';
    public string $phone = '';
    public int $countryId = 0;
    public string $city = '';
    public string $workcenter = '';
    public int $specialityId = 0;
    public bool $sanedRules = false;
    public bool $labRules = false;
    public bool $sanedRulesMailing = false;
    public bool $labRulesMailing = false;

    public array $countries;
    public array $specialities;

    public function mount(): void
    {
        $this->prepareCountries();
        $this->prepareSpecialities();
    }

    public function render(): View
    {
        return view('livewire.auth.register-guest-form');
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:250', // We'll check uniqueness in creation phase.
            'password' => ['required', new PasswordRule],
            'name' => 'required|max:45',
            'lastname1' => 'required|max:45',
            'lastname2' => 'required|max:45',
            'countryId' => 'required|exists:countries,id',
            'city' => 'required',
            'phone' => '',
            'workcenter' => 'required',
            'specialityId' => 'required|exists:specialities,id',
            'sanedRules' => 'required|accepted',
            'labRules' => 'required|accepted',
            'sanedRulesMailing' => '',
            'labRulesMailing' => '',
        ];
    }

    public function register()
    {
        $this->validateData();

        Auth::login($this->registerUser());

        return redirect()->route('directory');
    }

    protected function prepareCountries(): void
    {
        $this->countries = Country::all()->map(function (Country $country) {
            return ['text' => __($country->name), 'value' => $country->id];
        })->all();

        $this->countryId = $this->countries[0]['value'] ?? null;
    }

    protected function prepareSpecialities(): void
    {
        $this->specialities = Speciality::all()->map(function (Speciality $speciality) {
            return ['text' => __($speciality->name), 'value' => $speciality->id];
        })->all();

        $this->specialityId = $this->specialities[0]['value'] ?? null;
    }

    protected function validateData(): void
    {
        $this->resetValidation();

        try {
            $this->validate();
        } catch (ValidationException $e) {
            $this->dispatchBrowserEvent('scrollIntoView', [
                'elementId' => $e->validator->errors()->keys()[0],
            ]);

            throw $e;
        }
    }

    protected function registerUser(): User
    {
        $synchronizer = app(SanedUserSynchronizer::class);

        // If the user exists in Saned we won't allow the user to register
        // with provided email, because it's actually in use.

        if ($synchronizer->findByEmail($this->email)) {
            throw ValidationException::withMessages([
                'email' => trans('validation.unique', ['attribute' => __('email')]),
            ]);
        }

        // If user doesn't exist at all, we'll create him in in our database
        // and then we'll synchronize the data in Saned's database.

        $user = $this->createUser();

        $sanedUserId = $synchronizer->syncFromLocalToRemote($user);
        $synchronizer->updateRemotePasswordUsingId($sanedUserId, $this->password);

        $user->saned_user_id = $sanedUserId;
        $user->save();

        $user->notify(new Registered);

        return $user;
    }

    protected function createUser(): User
    {
        return User::create([
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'name' => $this->name,
            'lastname1' => $this->lastname1,
            'lastname2' => $this->lastname2,
            'phone' => $this->phone,
            'speciality_id' => $this->specialityId,
            'country_id' => $this->countryId,
            'city' => $this->city,
            'workcenter' => $this->workcenter,
            'registered_in_service' => true,
            'is_admin' => false,
            'is_coordinator' => false,
            'accepted_lab_mailing' => $this->labRulesMailing,
            'accepted_saned_mailing' => $this->sanedRulesMailing,
        ]);
    }
}
