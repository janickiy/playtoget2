ALTER TABLE `events`
  ADD COLUMN `banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `moderate`;

CREATE INDEX `idx_events_banned_date` ON `events` (`banned`, `date_from`);
