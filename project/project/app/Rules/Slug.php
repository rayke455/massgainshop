<?php

namespace App\Rules;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Slug implements ValidationRule
{
    public $slug;
    public function __construct($slug)
    {
        $this->slug = $slug;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = $this->slug;
        $theme = basicControl()->theme;
        $page = Page::where('slug', $slug)
            ->where('template_name', $theme)
            ->first();

        if ($page){
            $fail('The slug has already been taken.');
        }
    }
}
