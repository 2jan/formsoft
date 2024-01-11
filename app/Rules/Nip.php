<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Nip implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/^[0-9]{10}$/', (string) $value) === 1) {
            $digits = str_split($value);
            $checksum = (6*intval($digits[0]) + 5*intval($digits[1]) + 7*intval($digits[2]) + 2*intval($digits[3]) + 3*intval($digits[4]) + 4*intval($digits[5]) + 5*intval($digits[6]) + 6*intval($digits[7]) + 7*intval($digits[8]))%11;
            if (intval($digits[9]) == $checksum) {
                return;
            }
        }
        $fail('Proszę podać prawidłowy numer NIP (10 cyfr bez znaków rozdzielających).');
    }
}
