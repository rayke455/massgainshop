-- Removes ONLY the demo data created while testing the Zirka dashboard locally, by exact id.
-- (The earlier version deleted id RANGES, which would also have removed real imported services
--  that happened to get ids in the same range. Never use ranges here.)
-- Safe to run more than once. Do NOT run on a site where these exact ids belong to real data.
START TRANSACTION;

-- demo client account "zk_demo" and everything it created
SET @demo_user := (SELECT id FROM users WHERE username = 'zk_demo' AND email = 'zk_demo@example.com' LIMIT 1);
DELETE FROM orders          WHERE user_id = @demo_user;
DELETE FROM transactions    WHERE user_id = @demo_user;
DELETE FROM deposits        WHERE user_id = @demo_user;
DELETE FROM draft_mass_orders WHERE user_id = @demo_user;
DELETE FROM user_logins     WHERE user_id = @demo_user;
DELETE FROM users           WHERE id = @demo_user;

-- the 5 demo services (manual, no provider) and their demo categories/platforms
DELETE FROM services WHERE id IN (9001, 9002, 9003, 9004, 9005) AND api_provider_id IS NULL;
DELETE c FROM categories c
  LEFT JOIN services s ON s.category_id = c.id
 WHERE c.id IN (9001, 9002, 9003, 9004) AND s.id IS NULL;
DELETE m FROM social_media m
  LEFT JOIN categories c ON c.social_media_id = m.id
 WHERE m.id IN (9001, 9002, 9003) AND c.id IS NULL;

COMMIT;
