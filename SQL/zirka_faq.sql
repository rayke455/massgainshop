-- Zirka Social: replace the template FAQ (agency wording) with real SMM-panel answers.
-- Safe to run more than once.
SET NAMES utf8mb4;

UPDATE content_details SET description = JSON_OBJECT(
    'title', 'FAQ',
    'heading', 'Questions? We have [answers]',
    'button_name', 'Contact support',
    'description', 'Everything you need to know about ordering, paying and tracking on Zirka Social.'
) WHERE content_id = 72 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'How do I place an order?',
    'answer', 'Create a free account, add funds, then open Buy services. Pick the platform and service, paste the link to your post or profile, enter the quantity and confirm. The order starts automatically.')
WHERE content_id = 73 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'How do I add funds?',
    'answer', 'Go to Add funds, choose an amount and a payment method. With M-Pesa you get a prompt on your phone: enter your PIN and the balance updates within seconds. Card and bank transfer are also available.')
WHERE content_id = 74 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'How fast will my order start?',
    'answer', 'Most services start within minutes. Each service shows its expected speed in the name and description, for example 10k/day or Instant, so you know what to expect before you buy.')
WHERE content_id = 75 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'Do you need my password?',
    'answer', 'Never. We only need the public link to your post or profile. Make sure the account is public while the order runs, otherwise delivery can stall.')
WHERE content_id = 76 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'What if my followers or likes drop?',
    'answer', 'Services marked Refill are covered: open My orders and press Refill within the refill period and we top the order back up for free. Services without refill are not covered, so pick a refill service for long-term growth.')
WHERE content_id = 77 AND language_id = 1;

UPDATE content_details SET description = JSON_OBJECT('question', 'Can I cancel or get a refund?',
    'answer', 'If an order is canceled or only partly delivered, the undelivered part is refunded to your Zirka balance automatically, so you can use it for your next order.')
WHERE content_id = 78 AND language_id = 1;

-- Two extra questions (only added once).
INSERT INTO contents (name, theme, type, created_at, updated_at)
SELECT 'faq', 'light_green', 'multiple', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM content_details WHERE description LIKE '%How do I track my order?%');
SET @c1 = IF(ROW_COUNT() > 0, LAST_INSERT_ID(), NULL);
INSERT INTO content_details (content_id, language_id, description, created_at, updated_at)
SELECT @c1, 1, JSON_OBJECT('question', 'How do I track my order?',
    'answer', 'Use the order tracker on the home page: type your order ID to see live progress without logging in. Logged-in customers can also follow every order in My orders.'), NOW(), NOW()
FROM DUAL WHERE @c1 IS NOT NULL;

INSERT INTO contents (name, theme, type, created_at, updated_at)
SELECT 'faq', 'light_green', 'multiple', NOW(), NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM content_details WHERE description LIKE '%Do you have an API for resellers?%');
SET @c2 = IF(ROW_COUNT() > 0, LAST_INSERT_ID(), NULL);
INSERT INTO content_details (content_id, language_id, description, created_at, updated_at)
SELECT @c2, 1, JSON_OBJECT('question', 'Do you have an API for resellers?',
    'answer', 'Yes. Every account gets an API key under API settings, and the API docs page shows how to connect your own panel or app to Zirka Social.'), NOW(), NOW()
FROM DUAL WHERE @c2 IS NOT NULL;

-- Show the FAQ and order tracker on the home page (after testimonials / after services).
UPDATE page_details
SET sections = '["hero","achievement","about","why_choose_us","service","order_tracker","feature","how_it_works","testimonial","faq","payment_partner","blog","footer"]',
    content = REPLACE(
        REPLACE(content,
            '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[feature]]</div>',
            '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[order_tracker]]</div>\n                    <span class="delete-block">×</span>\n                    <span class="up-block">↑</span>\n                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[feature]]</div>'),
        '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[payment_partner]]</div>',
        '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[faq]]</div>\n                    <span class="delete-block">×</span>\n                    <span class="up-block">↑</span>\n                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[payment_partner]]</div>')
WHERE page_id = 1 AND content NOT LIKE '%[[faq]]%';
