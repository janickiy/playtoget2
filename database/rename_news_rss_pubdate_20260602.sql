DROP INDEX `idx_news_rss_sport_pubdate` ON `news_rss_sport`;

ALTER TABLE `news_rss_sport`
  CHANGE COLUMN `pubdate` `created_at` TIMESTAMP NULL DEFAULT NULL,
  ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;

UPDATE `news_rss_sport`
SET `updated_at` = `created_at`
WHERE `updated_at` IS NULL;

CREATE INDEX `idx_news_rss_sport_created_at` ON `news_rss_sport` (`created_at`);
