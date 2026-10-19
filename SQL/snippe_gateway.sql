-- Zirka Social: add the Snippe (Tanzania mobile money) payment gateway.
-- Safe to run more than once. Starts switched OFF: add your API key in
-- Admin -> Payment Methods -> Snippe (Tanzania), then switch it on.
-- Note: automatic gateways must have an id below 1000 (ids >= 1000 are treated as manual methods).
SET NAMES utf8mb4;

INSERT INTO gateways (id, code, name, sort_by, image, driver, status, parameters, currencies, extra_parameters,
                      supported_currency, receivable_currencies, description, currency_type, is_sandbox, environment,
                      is_manual, created_at, updated_at)
SELECT 51, 'snippe', 'Snippe (Tanzania)', 39, 'gateway/snippe.png', 'local', 0,
       '{"api_key":"","webhook_secret":""}',
       '{"0":{"TZS":"TZS"}}',
       NULL,
       '["TZS"]',
       '[{"name":"TZS","currency_symbol":"TSh","conversion_rate":"2631.84","min_limit":"500","max_limit":"5000000","percentage_charge":"0","fixed_charge":"0"}]',
       'Pay with M-Pesa, Airtel Money, Mixx by Yas or Halotel (Tanzania).',
       1, 0, 'live', NULL, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM gateways WHERE code = 'snippe')
  AND NOT EXISTS (SELECT 1 FROM gateways WHERE id = 51);
