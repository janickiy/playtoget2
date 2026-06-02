SET FOREIGN_KEY_CHECKS = 0;

RENAME TABLE
  `ftey1_accepted_event_members` TO `accepted_event_members`,
  `ftey1_attachment` TO `attachment`,
  `ftey1_comments` TO `comments`,
  `ftey1_communities` TO `communities`,
  `ftey1_communities_settings` TO `communities_settings`,
  `ftey1_community_roles` TO `community_roles`,
  `ftey1_content` TO `content`,
  `ftey1_events` TO `events`,
  `ftey1_feedback` TO `feedback`,
  `ftey1_friends` TO `friends`,
  `ftey1_geo_city` TO `geo_city`,
  `ftey1_geo_country` TO `geo_country`,
  `ftey1_geo_region` TO `geo_region`,
  `ftey1_geo_target` TO `geo_target`,
  `ftey1_likes` TO `likes`,
  `ftey1_log` TO `log`,
  `ftey1_mail_notification` TO `mail_notification`,
  `ftey1_messages` TO `messages`,
  `ftey1_news_rss_sport` TO `news_rss_sport`,
  `ftey1_occupations` TO `occupations`,
  `ftey1_photoalbums` TO `photoalbums`,
  `ftey1_photos` TO `photos`,
  `ftey1_sessions` TO `sessions`,
  `ftey1_share` TO `share`,
  `ftey1_sport_blocks` TO `sport_blocks`,
  `ftey1_sport_events` TO `sport_events`,
  `ftey1_sport_level` TO `sport_level`,
  `ftey1_sport_types` TO `sport_types`,
  `ftey1_user_activity` TO `user_activity`,
  `ftey1_users` TO `users`,
  `ftey1_users_roles` TO `users_roles`,
  `ftey1_users_sport_types` TO `users_sport_types`,
  `ftey1_usersettings` TO `usersettings`,
  `ftey1_video_views` TO `video_views`,
  `ftey1_videoalbums` TO `videoalbums`,
  `ftey1_videos` TO `videos`;

ALTER TABLE `accepted_event_members`
  RENAME COLUMN `id_member` TO `member_id`,
  RENAME COLUMN `id_event` TO `event_id`;

ALTER TABLE `attachment`
  RENAME COLUMN `id_content` TO `content_id`,
  RENAME COLUMN `id_photo` TO `photo_id`;

ALTER TABLE `comments`
  RENAME COLUMN `id_content` TO `content_id`,
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_behalf` TO `behalf_id`,
  RENAME COLUMN `id_parent` TO `parent_id`;

ALTER TABLE `communities_settings`
  RENAME COLUMN `id_community` TO `community_id`;

ALTER TABLE `community_roles`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_community` TO `community_id`;

ALTER TABLE `friends`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_friend` TO `friend_id`;

ALTER TABLE `geo_city`
  RENAME COLUMN `id_country` TO `country_id`,
  RENAME COLUMN `id_region` TO `region_id`;

ALTER TABLE `geo_region`
  RENAME COLUMN `id_country` TO `country_id`;

ALTER TABLE `geo_target`
  RENAME COLUMN `id_target` TO `target_id`,
  RENAME COLUMN `id_country` TO `country_id`,
  RENAME COLUMN `id_region` TO `region_id`,
  RENAME COLUMN `id_city` TO `city_id`;

ALTER TABLE `likes`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_content` TO `content_id`;

ALTER TABLE `log`
  RENAME COLUMN `id_user` TO `user_id`;

ALTER TABLE `messages`
  RENAME COLUMN `id_sender` TO `sender_id`,
  RENAME COLUMN `id_receiver` TO `receiver_id`;

ALTER TABLE `occupations`
  RENAME COLUMN `id_user` TO `user_id`;

ALTER TABLE `photoalbums`
  RENAME COLUMN `id_owner` TO `owner_id`;

ALTER TABLE `photos`
  RENAME COLUMN `id_photoalbum` TO `photoalbum_id`,
  RENAME COLUMN `id_owner` TO `owner_id`;

ALTER TABLE `sessions`
  RENAME COLUMN `id_session` TO `session_id`,
  RENAME COLUMN `id_user` TO `user_id`;

ALTER TABLE `share`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_content` TO `content_id`;

ALTER TABLE `sport_blocks`
  RENAME COLUMN `id_owner` TO `owner_id`;

ALTER TABLE `sport_types`
  RENAME COLUMN `id_parent` TO `parent_id`;

ALTER TABLE `user_activity`
  RENAME COLUMN `id_user` TO `user_id`;

ALTER TABLE `users_sport_types`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_sport_level` TO `sport_level_id`;

ALTER TABLE `usersettings`
  RENAME COLUMN `id_user` TO `user_id`;

ALTER TABLE `video_views`
  RENAME COLUMN `id_user` TO `user_id`,
  RENAME COLUMN `id_video` TO `video_id`;

ALTER TABLE `videoalbums`
  RENAME COLUMN `id_owner` TO `owner_id`;

ALTER TABLE `videos`
  RENAME COLUMN `id_videoalbum` TO `videoalbum_id`,
  RENAME COLUMN `id_owner` TO `owner_id`;

CREATE INDEX `idx_aem_event_member_type_role` ON `accepted_event_members` (`event_id`, `member_id`, `eventable_type`, `role`);
CREATE INDEX `idx_aem_member_role` ON `accepted_event_members` (`member_id`, `role`);
CREATE INDEX `idx_attachment_type_content` ON `attachment` (`type`, `content_id`);
CREATE INDEX `idx_attachment_photo` ON `attachment` (`photo_id`);
CREATE INDEX `idx_comments_type_content_created` ON `comments` (`commentable_type`, `content_id`, `created_at`);
CREATE INDEX `idx_comments_user_created` ON `comments` (`user_id`, `created_at`);
CREATE INDEX `idx_communities_type_moderate` ON `communities` (`type`, `moderate`);
CREATE INDEX `idx_communities_name` ON `communities` (`name`);
CREATE INDEX `idx_communities_settings_community` ON `communities_settings` (`community_id`);
CREATE INDEX `idx_community_roles_community_user_role` ON `community_roles` (`community_id`, `user_id`, `role`);
CREATE INDEX `idx_community_roles_user_role` ON `community_roles` (`user_id`, `role`);
CREATE INDEX `idx_events_moderate_date` ON `events` (`moderate`, `date_from`);
CREATE INDEX `idx_events_name` ON `events` (`name`);
CREATE INDEX `idx_feedback_time` ON `feedback` (`time`);
CREATE INDEX `idx_friends_user_status_friend` ON `friends` (`user_id`, `status`, `friend_id`);
CREATE INDEX `idx_friends_friend_status_user` ON `friends` (`friend_id`, `status`, `user_id`);
CREATE INDEX `idx_likes_content_type_user` ON `likes` (`content_id`, `likeable_type`, `user_id`);
CREATE INDEX `idx_likes_user_time` ON `likes` (`user_id`, `time`);
CREATE INDEX `idx_log_user_last_sign_in` ON `log` (`user_id`, `last_sign_in_at`);
CREATE INDEX `idx_messages_sender_receiver_status_created` ON `messages` (`sender_id`, `receiver_id`, `status`, `created_at`);
CREATE INDEX `idx_messages_receiver_sender_status_created` ON `messages` (`receiver_id`, `sender_id`, `status`, `created_at`);
CREATE INDEX `idx_news_rss_sport_pubdate` ON `news_rss_sport` (`pubdate`);
CREATE INDEX `idx_occupations_user_kind` ON `occupations` (`user_id`, `kind`);
CREATE INDEX `idx_photoalbums_owner_type` ON `photoalbums` (`owner_id`, `photoalbumable_type`);
CREATE INDEX `idx_photos_album_moderate_id` ON `photos` (`photoalbum_id`, `moderate`, `id`);
CREATE INDEX `idx_photos_owner_created` ON `photos` (`owner_id`, `created_at`);
CREATE INDEX `idx_sessions_user_expiration` ON `sessions` (`user_id`, `expiration_date`);
CREATE INDEX `idx_sessions_token` ON `sessions` (`token`);
CREATE INDEX `idx_share_content_type_user` ON `share` (`content_id`, `shareable_type`, `user_id`);
CREATE INDEX `idx_share_user_time` ON `share` (`user_id`, `time`);
CREATE INDEX `idx_sport_blocks_type_owner_active` ON `sport_blocks` (`type`, `owner_id`, `active`);
CREATE INDEX `idx_sport_blocks_name` ON `sport_blocks` (`name`);
CREATE INDEX `idx_sport_types_parent_name` ON `sport_types` (`parent_id`, `name`);
CREATE INDEX `idx_user_activity_user_last` ON `user_activity` (`user_id`, `last_activity`);
CREATE INDEX `idx_users_email` ON `users` (`email`);
CREATE INDEX `idx_users_confirmation_token` ON `users` (`confirmation_token`);
CREATE INDEX `idx_users_reset_password_token` ON `users` (`reset_password_token`);
CREATE INDEX `idx_users_status` ON `users` (`confirmed`, `banned`, `deleted`);
CREATE INDEX `idx_users_sport_types_user_level` ON `users_sport_types` (`user_id`, `sport_level_id`);
CREATE INDEX `idx_video_views_video_user_time` ON `video_views` (`video_id`, `user_id`, `time`);
CREATE INDEX `idx_videoalbums_owner_type` ON `videoalbums` (`owner_id`, `videoalbumable_type`);
CREATE INDEX `idx_videos_album_created` ON `videos` (`videoalbum_id`, `created_at`);
CREATE INDEX `idx_videos_owner_created` ON `videos` (`owner_id`, `created_at`);

SET FOREIGN_KEY_CHECKS = 1;
