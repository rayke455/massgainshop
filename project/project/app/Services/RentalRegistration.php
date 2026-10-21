<?php

namespace App\Services;

use App\Models\RentalPanel;
use Illuminate\Support\Facades\Validator;

class RentalRegistration
{
    public static function fields(?RentalPanel $panel): array
    {
        if (!$panel || !$panel->allows('accounts')) return [];
        $fields = [];
        foreach (range(1, 5) as $index) {
            $key = 'field_'.$index;
            $field = $panel->workspace()['registration_fields'][$key] ?? [];
            if (trim((string) ($field['label'] ?? '')) === '') continue;
            $fields[$key] = ['label' => $field['label'], 'required' => (bool) ($field['required'] ?? false)];
        }
        return $fields;
    }

    public static function rules(?RentalPanel $panel): array
    {
        $fields = self::fields($panel);
        $rules = ['rental_fields' => $fields ? ['nullable', 'array:'.implode(',', array_keys($fields))] : ['prohibited']];
        foreach ($fields as $key => $field) {
            $rules['rental_fields.'.$key] = [$field['required'] ? 'required' : 'nullable', 'string', 'max:200'];
        }
        return $rules;
    }

    public static function answers(?RentalPanel $panel, array $input): array
    {
        if (!$panel) return [];
        Validator::make($input, self::rules($panel))->validate();
        $answers = [];
        foreach (self::fields($panel) as $key => $field) {
            $value = trim((string) data_get($input, 'rental_fields.'.$key, ''));
            if ($value !== '') $answers[$key] = ['label' => $field['label'], 'value' => $value];
        }
        return $answers;
    }
}
