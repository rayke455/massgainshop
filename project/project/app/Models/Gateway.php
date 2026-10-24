<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'sort_by',
        'image',
        'driver',
        'status',
        'parameters',
        'currencies',
        'description',
        'extra_parameters',
        'currency',
        'symbol',
        'is_sandbox',
        'environment',
        'min_amount',
        'max_amount',
        'percentage_charge',
        'fixed_charge',
        'convention_rate',
        'supported_currency',
        'receivable_currencies',
        'note'
    ];
    protected $casts = [
        'currency' => 'object',
        'supported_currency' => 'object',
        'receivable_currencies' => 'object',
        'parameters' => 'object',
        'currencies' => 'object',
        'extra_parameters' => 'object',
    ];

    public function scopeAutomatic()
    {
        return $this->where('id', '<', 1000);
    }

    public function scopeManual()
    {
        return $this->where('id', '>=', 1000);
    }

    public function countGatewayCurrency()
    {
        if (empty($this->currencies)) {
            return 0;
        }

        $currencyLists = null;
        if (is_array($this->currencies)) {
            $currencyLists = $this->currencies[0] ?? $this->currencies[1] ?? $this->currencies;
        } elseif (is_object($this->currencies)) {
            $currencyLists = $this->currencies->{0} ?? $this->currencies->{1} ?? $this->currencies;
        }

        if (empty($currencyLists)) {
            return 0;
        }

        if (is_array($currencyLists) || is_countable($currencyLists)) {
            return count($currencyLists);
        }

        if (is_object($currencyLists)) {
            return count(get_object_vars($currencyLists));
        }

        return 0;
    }

    public function countSupportedCurrency()
    {
        if (empty($this->supported_currency)) {
            return 0;
        }

        if (is_array($this->supported_currency) || is_countable($this->supported_currency)) {
            return count($this->supported_currency);
        }

        if (is_object($this->supported_currency)) {
            return count(get_object_vars($this->supported_currency));
        }

        return 0;
    }
}
