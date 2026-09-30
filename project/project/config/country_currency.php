<?php
// ISO country => local currency, used to pick a visitor's currency automatically on their first visit.
// Only currencies that are active in Admin → Currencies are ever applied.
return [
    // Africa
    'KE' => 'KES', 'UG' => 'UGX', 'TZ' => 'TZS', 'RW' => 'RWF', 'BI' => 'BIF', 'ET' => 'ETB', 'SO' => 'SOS', 'DJ' => 'DJF',
    'ER' => 'ERN', 'SS' => 'SSP', 'SD' => 'SDG', 'EG' => 'EGP', 'LY' => 'LYD', 'TN' => 'TND', 'DZ' => 'DZD', 'MA' => 'MAD',
    'NG' => 'NGN', 'GH' => 'GHS', 'LR' => 'LRD', 'SL' => 'SLE', 'GM' => 'GMD', 'GN' => 'GNF', 'CV' => 'CVE', 'MR' => 'MRU',
    'SN' => 'XOF', 'CI' => 'XOF', 'ML' => 'XOF', 'BF' => 'XOF', 'NE' => 'XOF', 'TG' => 'XOF', 'BJ' => 'XOF', 'GW' => 'XOF',
    'CM' => 'XAF', 'GA' => 'XAF', 'CG' => 'XAF', 'TD' => 'XAF', 'CF' => 'XAF', 'GQ' => 'XAF', 'CD' => 'CDF', 'AO' => 'AOA',
    'ZA' => 'ZAR', 'NA' => 'NAD', 'BW' => 'BWP', 'LS' => 'LSL', 'SZ' => 'SZL', 'ZM' => 'ZMW', 'ZW' => 'ZWG', 'MW' => 'MWK',
    'MZ' => 'MZN', 'MG' => 'MGA', 'MU' => 'MUR', 'SC' => 'SCR', 'KM' => 'KMF', 'ST' => 'STN', 'SH' => 'SHP',
    // Europe
    'GB' => 'GBP', 'IE' => 'EUR', 'FR' => 'EUR', 'DE' => 'EUR', 'IT' => 'EUR', 'ES' => 'EUR', 'PT' => 'EUR', 'NL' => 'EUR',
    'BE' => 'EUR', 'LU' => 'EUR', 'AT' => 'EUR', 'FI' => 'EUR', 'GR' => 'EUR', 'CY' => 'EUR', 'MT' => 'EUR', 'SK' => 'EUR',
    'SI' => 'EUR', 'EE' => 'EUR', 'LV' => 'EUR', 'LT' => 'EUR', 'HR' => 'EUR', 'MC' => 'EUR', 'SM' => 'EUR', 'VA' => 'EUR',
    'AD' => 'EUR', 'ME' => 'EUR', 'XK' => 'EUR', 'CH' => 'CHF', 'LI' => 'CHF', 'NO' => 'NOK', 'SE' => 'SEK', 'DK' => 'DKK',
    'IS' => 'ISK', 'PL' => 'PLN', 'CZ' => 'CZK', 'HU' => 'HUF', 'RO' => 'RON', 'MD' => 'MDL', 'BG' => 'BGN', 'RS' => 'RSD',
    'BA' => 'BAM', 'MK' => 'MKD', 'AL' => 'ALL', 'UA' => 'UAH', 'BY' => 'BYN', 'RU' => 'RUB', 'TR' => 'TRY', 'GE' => 'GEL',
    'AM' => 'AMD', 'AZ' => 'AZN', 'GI' => 'GIP',
    // Americas
    'US' => 'USD', 'CA' => 'CAD', 'MX' => 'MXN', 'BR' => 'BRL', 'AR' => 'ARS', 'CL' => 'CLP', 'CO' => 'COP', 'PE' => 'PEN',
    'VE' => 'VES', 'EC' => 'USD', 'BO' => 'BOB', 'PY' => 'PYG', 'UY' => 'UYU', 'GY' => 'GYD', 'SR' => 'SRD', 'PA' => 'PAB',
    'CR' => 'CRC', 'NI' => 'NIO', 'HN' => 'HNL', 'SV' => 'USD', 'GT' => 'GTQ', 'BZ' => 'BZD', 'DO' => 'DOP', 'HT' => 'HTG',
    'CU' => 'CUP', 'JM' => 'JMD', 'TT' => 'TTD', 'BB' => 'BBD', 'BS' => 'BSD', 'BM' => 'BMD', 'KY' => 'KYD', 'AW' => 'AWG',
    'CW' => 'XCG', 'AG' => 'XCD', 'DM' => 'XCD', 'GD' => 'XCD', 'KN' => 'XCD', 'LC' => 'XCD', 'VC' => 'XCD', 'PR' => 'USD',
    'FK' => 'FKP',
    // Middle East & Asia
    'AE' => 'AED', 'SA' => 'SAR', 'QA' => 'QAR', 'KW' => 'KWD', 'BH' => 'BHD', 'OM' => 'OMR', 'JO' => 'JOD', 'LB' => 'LBP',
    'SY' => 'SYP', 'IQ' => 'IQD', 'IR' => 'IRR', 'IL' => 'ILS', 'PS' => 'ILS', 'YE' => 'YER', 'AF' => 'AFN', 'PK' => 'PKR',
    'IN' => 'INR', 'BD' => 'BDT', 'LK' => 'LKR', 'NP' => 'NPR', 'BT' => 'BTN', 'MV' => 'MVR', 'CN' => 'CNY', 'HK' => 'HKD',
    'MO' => 'MOP', 'TW' => 'TWD', 'JP' => 'JPY', 'KR' => 'KRW', 'MN' => 'MNT', 'KZ' => 'KZT', 'UZ' => 'UZS', 'KG' => 'KGS',
    'TJ' => 'TJS', 'TM' => 'TMT', 'ID' => 'IDR', 'MY' => 'MYR', 'SG' => 'SGD', 'BN' => 'BND', 'PH' => 'PHP', 'TH' => 'THB',
    'VN' => 'VND', 'KH' => 'KHR', 'LA' => 'LAK', 'MM' => 'MMK', 'TL' => 'USD',
    // Oceania
    'AU' => 'AUD', 'NZ' => 'NZD', 'FJ' => 'FJD', 'PG' => 'PGK', 'SB' => 'SBD', 'VU' => 'VUV', 'WS' => 'WST', 'TO' => 'TOP',
    'NC' => 'XPF', 'PF' => 'XPF',
];
