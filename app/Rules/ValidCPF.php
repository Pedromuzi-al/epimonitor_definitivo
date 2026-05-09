<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCPF implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Remove non-numeric characters
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Check if it has 11 digits
        if (strlen($cpf) != 11) {
            return false;
        }

        // Check if all digits are the same
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validate first check digit
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $firstDigit = 11 - ($sum % 11);
        $firstDigit = $firstDigit >= 10 ? 0 : $firstDigit;

        if ((int)$cpf[9] != $firstDigit) {
            return false;
        }

        // Validate second check digit
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $secondDigit = 11 - ($sum % 11);
        $secondDigit = $secondDigit >= 10 ? 0 : $secondDigit;

        return (int)$cpf[10] == $secondDigit;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'CPF inválido.';
    }
}
