-- Massfame / Zirka Social: add Swychr Connect (Payin/Collection) payment gateway.
-- Run this in your live database (phpMyAdmin -> SQL tab).
-- Safe to run multiple times: it removes any partial swychr row first and assigns the next automatic ID (< 1000).

SET NAMES utf8mb4;

DELETE FROM `gateways` WHERE `code` = 'swychr';

INSERT INTO `gateways` (`id`, `code`, `name`, `sort_by`, `image`, `driver`, `status`, `parameters`, `currencies`, `extra_parameters`,
                        `supported_currency`, `receivable_currencies`, `description`, `currency_type`, `is_sandbox`, `environment`,
                        `is_manual`, `created_at`, `updated_at`)
SELECT (SELECT IFNULL(MAX(g.id), 50) + 1 FROM `gateways` g WHERE g.id < 1000),
       'swychr',
       'Swychr Pay',
       1,
       'gateway/swychr.png',
       'local',
       0,
       '{"api_key":"","webhook_secret":"","country_code":"KE","pass_digital_charge":"0"}',
       '{"0":{"KES":"KES","USD":"USD","NGN":"NGN","GHS":"GHS","XAF":"XAF","XOF":"XOF","UGX":"UGX","TZS":"TZS"}}',
       '{"webhook_url":"ipn"}',
       '["KES","USD","NGN","GHS","XAF","XOF","UGX","TZS"]',
       '[{"name":"KES","currency_symbol":"KSh","conversion_rate":"130.00","min_limit":"100","max_limit":"500000","percentage_charge":"0","fixed_charge":"0"},{"name":"USD","currency_symbol":"$","conversion_rate":"1","min_limit":"1","max_limit":"5000","percentage_charge":"0","fixed_charge":"0"},{"name":"NGN","currency_symbol":"₦","conversion_rate":"1600.00","min_limit":"1000","max_limit":"5000000","percentage_charge":"0","fixed_charge":"0"},{"name":"GHS","currency_symbol":"GH₵","conversion_rate":"15.50","min_limit":"10","max_limit":"50000","percentage_charge":"0","fixed_charge":"0"}]',
       'Pay with Mobile Money (M-Pesa, MTN, Airtel, Orange, Telecel) and Card/Crypto with Swychr Pay.',
       1,
       0,
       'live',
       NULL,
       NOW(),
       NOW();
