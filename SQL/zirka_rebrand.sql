-- Zirka Social rebrand for the light_green theme.
-- Run AFTER install.sql. Safe to re-run: every statement is an idempotent UPDATE.
-- Only text/branding fields are touched; images, links and other themes are left alone.

SET NAMES utf8mb4;

-- Site identity + logo/favicon (files live in project/project/assets/upload/logo/)
UPDATE `basic_controls`
SET `site_title`      = 'Zirka Social',
    `theme`           = 'light_green',
    `logo`            = 'logo/zirka-logo.svg',
    `logo_driver`     = 'local',
    `favicon`         = 'logo/zirka-favicon.svg',
    `favicon_driver`  = 'local',
    `cookie_url`      = '/cookie-policy'
WHERE `id` = 1;

-- Brand colours (editable later in Admin -> Theme Color)
UPDATE `theme_colors`
SET `light_green_primary_color`   = '#5B4CF0',
    `light_green_secondary_color` = '#F5B82E',
    `light_green_hero_color`      = '#5B4CF0'
WHERE `id` = 1;

-- Hero (content_id 1, English)
UPDATE `content_details`
SET `description` = JSON_SET(`description`,
        '$.title', 'Social growth, simplified',
        '$.heading', 'Grow every social account from one simple dashboard',
        '$.short_description', 'Zirka Social delivers real engagement for Instagram, TikTok, YouTube and more — fast starts, fair prices and support that actually answers.',
        '$.button_name', 'Start growing',
        '$.video_text', 'See how it works')
WHERE `content_id` = 1 AND `language_id` = 1;

-- Feature strip (content_id 2-4)
UPDATE `content_details` SET `description` = JSON_SET(`description`,
        '$.title', 'Starts in minutes',
        '$.short_description', 'Most orders begin processing automatically as soon as you place them.')
WHERE `content_id` = 2 AND `language_id` = 1;

UPDATE `content_details` SET `description` = JSON_SET(`description`,
        '$.title', 'Built for resellers',
        '$.short_description', 'Bulk orders, drip-feed and a full API so you can scale without extra work.')
WHERE `content_id` = 3 AND `language_id` = 1;

UPDATE `content_details` SET `description` = JSON_SET(`description`,
        '$.title', 'Transparent pricing',
        '$.short_description', 'Clear per-1000 rates with no hidden fees and refills on eligible services.')
WHERE `content_id` = 4 AND `language_id` = 1;

-- Footer (content_id 90)
UPDATE `content_details`
SET `description` = JSON_SET(`description`,
        '$.short_description', 'Zirka Social helps creators, brands and agencies grow on every major platform — quickly, affordably and safely.',
        '$.news_letter_heading', 'Tips, new services and deals. One email a month, no spam.',
        '$.copyright', CONCAT('© ', YEAR(CURDATE()), ' Zirka Social. All rights reserved.'))
WHERE `content_id` = 90 AND `language_id` = 1;

-- Home page SEO (page id 1 = light_green home)
UPDATE `pages`
SET `meta_title`       = 'Zirka Social — Fast, Affordable Social Media Growth',
    `meta_description` = 'Grow Instagram, TikTok, YouTube and more with Zirka Social. Instant starts, transparent pricing, bulk orders and a reseller API.',
    `og_description`   = 'Grow every social account from one simple dashboard with Zirka Social.'
WHERE `id` = 1;

-- Remove the original vendor's domains from customer-facing content.
-- Mass-order help text linked customers to the vendor's service list; point it at our own /services.
UPDATE `content_details`
SET `description` = REPLACE(REPLACE(`description`,
        'https:\\/\\/bugfinder.com\\/services', '\\/services'),
        'https:\\/\\/bugfinder.net\\/services', '\\/services')
WHERE `description` LIKE '%bugfinder%services%';

-- Mass-order example line used the vendor's site as the sample link.
UPDATE `content_details`
SET `description` = REPLACE(`description`, 'https:\\/\\/bugfinder.net\\/', 'https:\\/\\/instagram.com\\/yourprofile')
WHERE `description` LIKE '%bugfinder.net%';

-- Section buttons pointed at the vendor's site. Relative links work on any domain.
-- (Admin requires a full URL when you next edit these, e.g. https://yourdomain.com/register.)
UPDATE `contents` SET `media` = JSON_SET(`media`, '$.button_link', '/register')
WHERE `id` = 1 AND `name` = 'hero' AND `theme` = 'light_green';
UPDATE `contents` SET `media` = JSON_SET(`media`, '$.button_link', '/contact')
WHERE `theme` = 'light_green' AND `name` IN ('about', 'faq') AND `media` LIKE '%bugfinder%';
UPDATE `contents` SET `media` = JSON_SET(`media`, '$.button_link', '/')
WHERE `media` LIKE '%"button_link":"https:\\\\/\\\\/bugfinder%';

-- Old product name in body copy (sections, pages, FAQs)
UPDATE `content_details` SET `description` = REPLACE(`description`, 'SMM Matrix', 'Zirka Social')
WHERE `description` LIKE '%SMM Matrix%';
UPDATE `page_details` SET `content` = REPLACE(`content`, 'SMM Matrix', 'Zirka Social')
WHERE `content` LIKE '%SMM Matrix%';

-- PWA install popup (all themes): our name, and no hardcoded domain (the template shows the live host).
UPDATE `content_details` cd
JOIN `contents` c ON c.id = cd.content_id AND c.name = 'pwa_popup' AND c.type = 'single'
SET cd.description = JSON_SET(cd.description, '$.title', 'Zirka Social', '$.domain_name', '');

-- Sign-up page has its own heading; the breadcrumb banner just pushed the form down.
UPDATE `pages` SET `breadcrumb_status` = 0 WHERE `template_name` = 'light_green' AND `slug` = 'register';

-- SMMGen-style refresh: royal blue brand colour (still editable in Admin -> Theme Color)
UPDATE `theme_colors`
SET `light_green_primary_color` = '#1D4ED8',
    `light_green_hero_color`    = '#1D4ED8'
WHERE `id` = 1;

-- Hero: [brackets] mark the highlighted part of the headline; `title` is now the trust line above it.
UPDATE `content_details`
SET `description` = JSON_SET(`description`,
        '$.title', 'Loved by creators, brands & resellers',
        '$.heading', 'The SMM panel for [fast, reliable] social media growth',
        '$.button_name', 'Sign up free')
WHERE `content_id` = 1 AND `language_id` = 1;

-- Home page order: hero -> stats -> about -> why choose us -> platforms -> features -> ...
-- Only runs while the page still has the original order, so re-running never swaps back.
UPDATE `page_details`
SET `content` = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(`content`,
        '[[feature]]', '[[zk_tmp_a]]'), '[[achievement]]', '[[feature]]'), '[[zk_tmp_a]]', '[[achievement]]'),
        '[[service]]', '[[zk_tmp_b]]'), '[[why_choose_us]]', '[[service]]'), '[[zk_tmp_b]]', '[[why_choose_us]]'),
    `sections` = '["hero","achievement","about","why_choose_us","service","feature","how_it_works","testimonial","payment_partner","blog","footer"]'
WHERE `page_id` = 1
  AND `sections` = '["hero","feature","about","service","why_choose_us","achievement","how_it_works","testimonial","payment_partner","blog","footer"]';

-- Admin panel logos: normal logo on the light header, white-text logo on the dark sidebar.
UPDATE `basic_controls`
SET `admin_logo`                  = 'logo/zirka-logo.svg',
    `admin_logo_driver`           = 'local',
    `admin_dark_mode_logo`        = 'logo/zirka-logo-light.svg',
    `admin_dark_mode_logo_driver` = 'local'
WHERE `id` = 1;

-- Multi-currency: keep full precision for market rates (same as migration 2026_09_30_120000).
ALTER TABLE `currencies` MODIFY `conversion_rate` DECIMAL(24,10) NOT NULL DEFAULT 0;

-- M-Pesa: optional Till number (Buy Goods) field, and drop the placeholder webhook so the real
-- https://yourdomain/mpesa/webhook address is used automatically.
UPDATE `gateways`
SET `parameters` = JSON_SET(`parameters`, '$.till_number', COALESCE(JSON_UNQUOTE(JSON_EXTRACT(`parameters`, '$.till_number')), ''), '$.webhook', '')
WHERE `code` = 'mpesa'
  AND (JSON_EXTRACT(`parameters`, '$.till_number') IS NULL
       OR JSON_UNQUOTE(JSON_EXTRACT(`parameters`, '$.webhook')) LIKE '%localhost%');

-- Profit tracking: what the provider charged for each order (same as migration 2026_09_30_140000).
SET @zk_col := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'provider_cost');
SET @zk_sql := IF(@zk_col = 0, 'ALTER TABLE `orders` ADD `provider_cost` DECIMAL(18,8) NULL AFTER `price`', 'SELECT 1');
PREPARE zk_stmt FROM @zk_sql; EXECUTE zk_stmt; DEALLOCATE PREPARE zk_stmt;
UPDATE content_details
SET description = REPLACE(description, 'SMM \\r\\nMatrix', 'Zirka Social')
WHERE description LIKE '%SMM%Matrix%';
-- Other themes still carried a different sample brand ("SMM Forest", "#1Top SMMPanel").
UPDATE content_details SET description = REPLACE(description, 'SMM Forest', 'Zirka Social') WHERE description LIKE '%SMM Forest%';
UPDATE content_details SET description = REPLACE(description, '#1Top SMMPanel', 'Zirka Social') WHERE description LIKE '%#1Top SMMPanel%';
UPDATE content_details SET description = REPLACE(description, '1Top SMMPanel', 'Zirka Social') WHERE description LIKE '%1Top SMMPanel%';

-- Testimonial text named another panel.
UPDATE content_details SET description = REPLACE(description, 'Binary SMM', 'Zirka Social') WHERE description LIKE '%Binary SMM%';
-- Broken JSON in notification template placeholders made their edit pages crash.
UPDATE notification_templates SET short_keys = '{"amount":"Amount","feedback":"Admin feedback","charge":"Payment Charge","gateway_name":"Gateway Name","transaction":"Transaction Id"}' WHERE template_key = 'PAYMENT_REJECTED';
UPDATE notification_templates SET short_keys = '{"domain":"Domain name","username":"Username"}' WHERE template_key IN ('AUTOMATICALLY_RENEW_CHILD_PANEL','FAIL_AUTOMATICALLY_RENEW_CHILD_PANEL');
UPDATE notification_templates SET short_keys = '{"username":"Username"}' WHERE template_key = 'ADDED_USER_CHILD_PANEL' AND JSON_VALID(short_keys) = 0;
