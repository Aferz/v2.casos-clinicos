<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRule implements Rule
{
    public function passes($attribute, $value)
    {
        $uppercase = preg_match('@[A-Z]@', $value);
        $lowercase = preg_match('@[a-z]@', $value);
        $number = preg_match('@[0-9]@', $value);
        $symbol = preg_match('@(\%|\$|\&|\!)@', $value);

        return $uppercase
            && $lowercase
            && $number
            && $symbol
            && strlen($value) >= config('cc.auth.password_min_length')
            && strlen($value) <= config('cc.auth.password_max_length');
    }

    public function message()
    {
        return __('Password doesn\'t meet the requirements. It must have at least 8 characters, one capital letter, one small letter, one number and one symbol (%, $, &, !)');
    }
}
