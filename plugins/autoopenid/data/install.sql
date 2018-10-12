--
-- Структура таблицы `prefix_autoopenid_tmp`
--

CREATE TABLE IF NOT EXISTS `prefix_autoopenid_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `service_id` varchar(250) NOT NULL,
  `date` datetime NOT NULL,
  `confirm_mail_key` varchar(32) NOT NULL,
  `confirm_mail` varchar(250) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`),
  KEY `service_type` (`service_type`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `prefix_autoopenid_user`
--

CREATE TABLE IF NOT EXISTS `prefix_autoopenid_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `service_id` varchar(250) NOT NULL,
  `date` datetime NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `service_type` (`service_type`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- fix user table
ALTER TABLE `prefix_user` CHANGE `user_mail` `user_mail` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;