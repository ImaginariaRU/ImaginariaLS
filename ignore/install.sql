DROP TABLE IF EXISTS `prefix_ignore`;

CREATE TABLE `prefix_ignore` (
  `ignore_id` int(9) NOT NULL auto_increment,
  `user_id` int(9) NOT NULL,
  `ignore_target_id` int(9) NOT NULL,
  `ignore_types` varchar(100) NOT NULL,
  `ignore_date_add` datetime NOT NULL,
  `ignore_reason` varchar(250) default NULL,
  UNIQUE KEY `idxIgnore` (`user_id`,`ignore_target_id`),
  KEY `ignore_id` (`ignore_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `prefix_user` ADD COLUMN `user_count_ignore` INT(9) DEFAULT 0 NULL AFTER `user_settings_timezone`; 