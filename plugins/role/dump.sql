CREATE TABLE `prefix_role` (
  `role_id` int(11) NOT NULL auto_increment,
  `role_name` varchar(255) default NULL,
  `role_acl` longtext,
  `role_text` text,
  `role_rating` float(9,3) NOT NULL default '0.000',
  `role_rating_use` tinyint(1) NOT NULL default '0',
  `role_reg` tinyint(1) NOT NULL default '0',
  `role_date_add` datetime NOT NULL default '0000-00-00 00:00:00',
  `role_date_edit` datetime NOT NULL default '0000-00-00 00:00:00',
  `role_avatar` varchar(255) default NULL,
  `role_place` text,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE `prefix_role_user` (
  `role_user_id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`role_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE `prefix_role_users` (
  `user_id` int(11) NOT NULL,
  `role_acl` longtext,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `prefix_role_place_block` (
  `place_id` int(11) NOT NULL auto_increment,
  `role_id` int(11) NOT NULL default '0',
  `place_url` varchar(255) default NULL,
  `block_position` int(11) NOT NULL default '0',
  PRIMARY KEY  (`place_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `prefix_comment` ADD `comment_date_edit` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL ,
ADD `comment_edit_user_id` INT( 11 ) DEFAULT '0' NOT NULL ;