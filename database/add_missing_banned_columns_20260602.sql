ALTER TABLE `communities`
  ADD COLUMN `banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `photos`
  ADD COLUMN `banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `owner_id`;

ALTER TABLE `videos`
  ADD COLUMN `banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `owner_id`;

ALTER TABLE `sport_blocks`
  ADD COLUMN `banned` TINYINT(1) NOT NULL DEFAULT 0 AFTER `active`;

CREATE INDEX `idx_communities_banned_type` ON `communities` (`banned`, `type`);
CREATE INDEX `idx_photos_banned_album` ON `photos` (`banned`, `photoalbum_id`);
CREATE INDEX `idx_videos_banned_album` ON `videos` (`banned`, `videoalbum_id`);
CREATE INDEX `idx_sport_blocks_banned_type` ON `sport_blocks` (`banned`, `type`);
