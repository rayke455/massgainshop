<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneLength implements ValidationRule
{
    protected $validLengths;

    public function __construct($validLengths)
    {
        $this->validLengths = $validLengths;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validLengths = $this->validLengths;

        if (!is_array($validLengths)){
                if (strlen($value) != $validLengths) {
                    $fail("The $attribute length must be " . $validLengths . ' digits.');
                }
        }else{
            if (!in_array(strlen($value), $validLengths)) {
                    $fail("The $attribute length must be one of " . implode(', ', $validLengths) . ' digits.');
                }
        }
    }
}
