/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table smallurl_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_access`;

CREATE TABLE `smallurl_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UID` int(11) NOT NULL,
  `can_smallurl` int(11) NOT NULL,
  `can_smallimage` int(11) NOT NULL,
  `can_smallpaste` int(11) NOT NULL,
  `admin_dash_counters` int(11) NOT NULL,
  `admin_dash_traffic` int(11) NOT NULL,
  `admin_dash_info` int(11) NOT NULL,
  `admin_users_list` int(11) NOT NULL,
  `admin_users_modify` int(11) NOT NULL,
  `admin_short_view` int(11) NOT NULL,
  `admin_short_disable` int(11) NOT NULL,
  `admin_short_modify` int(11) NOT NULL,
  `admin_server_view` int(11) NOT NULL,
  `admin_server_git` int(11) NOT NULL,
  `admin_server_new` int(11) NOT NULL,
  `admin_ticket_view` int(11) NOT NULL,
  `admin_ticket_smallurl` int(11) NOT NULL,
  `admin_ticket_smallpaste` int(11) NOT NULL,
  `admin_ticket_smallimage` int(11) NOT NULL,
  `admin_flag_view` int(11) NOT NULL,
  `admin_flag_modify` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_api
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_api`;

CREATE TABLE `smallurl_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` text NOT NULL,
  `domain` text NOT NULL,
  `key` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table smallurl_apps
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_apps`;

CREATE TABLE `smallurl_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `desc` text NOT NULL,
  `official` int(11) NOT NULL DEFAULT '0',
  `callback` text NOT NULL,
  `user` int(11) NOT NULL,
  `perms` text NOT NULL,
  `pubtoken` text NOT NULL,
  `privtoken` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_auth`;

CREATE TABLE `smallurl_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `app` int(11) NOT NULL,
  `usertoken` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_badges
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_badges`;

CREATE TABLE `smallurl_badges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `colour` text NOT NULL,
  `desc` text NOT NULL,
  `longdesc` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '-1' COMMENT 'Level required to auto aquire this.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_blacklist
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_blacklist`;

CREATE TABLE `smallurl_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` text NOT NULL,
  `type` int(11) NOT NULL COMMENT '0 - Word, 1 - URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table smallurl_browsers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_browsers`;

CREATE TABLE `smallurl_browsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agentstring` text NOT NULL,
  `agentname` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_clicks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_clicks`;

CREATE TABLE `smallurl_clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smallurl` int(11) NOT NULL,
  `useragent` text NOT NULL,
  `ip` text NOT NULL,
  `clienthash` text NOT NULL,
  `stamp` int(11) NOT NULL,
  `ref` text NOT NULL,
  `lat` text NOT NULL,
  `lng` text NOT NULL,
  `country` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_dev
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_dev`;

CREATE TABLE `smallurl_dev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `user` int(11) NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_entries
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_entries`;

CREATE TABLE `smallurl_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v3_issue` tinyint(1) DEFAULT NULL,
  `content` text NOT NULL,
  `desc` text NOT NULL,
  `short` varchar(255) NOT NULL,
  `custom` int(11) NOT NULL,
  `stamp` int(11) NOT NULL,
  `uses` int(11) NOT NULL COMMENT '(DEPRECATED)',
  `user` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  `device` int(11) NOT NULL COMMENT '0-Web,1-ChromeExt,2-MobileSite,3-MobileApp,4-API',
  `nocache` int(11) NOT NULL,
  `client_hash` text NOT NULL,
  `ipaddr` text NOT NULL,
  `lat` text NOT NULL,
  `lng` text NOT NULL,
  `country` text NOT NULL,
  `useragent` text NOT NULL,
  `suspended` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT 'URL Type, Screenshot, Paste etc',
  `via` int(11) NOT NULL COMMENT 'App',
  `flagged` int(11) NOT NULL COMMENT 'When exceeded max clicks per 2hr',
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`short`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table smallurl_geo_cache
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_geo_cache`;

CREATE TABLE `smallurl_geo_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timezone` text NOT NULL,
  `lat` text NOT NULL,
  `lng` text NOT NULL,
  `country` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_ipbl
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_ipbl`;

CREATE TABLE `smallurl_ipbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_lng
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_lng`;

CREATE TABLE `smallurl_lng` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `text_node` int(11) NOT NULL,
  `text` text NOT NULL,
  `lang` int(11) NOT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_lngcode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_lngcode`;

CREATE TABLE `smallurl_lngcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `code` text NOT NULL,
  `country` text NOT NULL,
  `name` text NOT NULL,
  `approved` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_lngnode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_lngnode`;

CREATE TABLE `smallurl_lngnode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_id` text NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_notifications`;

CREATE TABLE `smallurl_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `url` text NOT NULL,
  `stamp` int(11) NOT NULL,
  `read` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_pages`;

CREATE TABLE `smallurl_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` text NOT NULL,
  `domain` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_report
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_report`;

CREATE TABLE `smallurl_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1=url, 2=user, 3=app',
  `item` int(11) NOT NULL,
  `user` text NOT NULL,
  `reason` text NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_roles`;

CREATE TABLE `smallurl_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `desc` text NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_support_reply
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_support_reply`;

CREATE TABLE `smallurl_support_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `stamp` int(11) NOT NULL,
  `thread` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_support_thread
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_support_thread`;

CREATE TABLE `smallurl_support_thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short` text NOT NULL,
  `user` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `stamp` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `unread` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_ubadges
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_ubadges`;

CREATE TABLE `smallurl_ubadges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `badge` int(11) NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_user_meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_user_meta`;

CREATE TABLE `smallurl_user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table smallurl_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_users`;

CREATE TABLE `smallurl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pin` int(11) NOT NULL,
  `balance` text NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `role` int(11) NOT NULL DEFAULT '4',
  `enabled` int(11) NOT NULL DEFAULT '1',
  `regstamp` int(11) NOT NULL,
  `regip` text NOT NULL,
  `verified` int(11) NOT NULL,
  `verifykey` text NOT NULL,
  `passreset` int(11) NOT NULL,
  `passresetkey` text NOT NULL,
  `hidegeo` int(11) NOT NULL COMMENT 'Hide Users Geolocation',
  `autopriv` int(11) NOT NULL,
  `autosafe` int(11) NOT NULL,
  `google_id` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table smallurl_wot
# ------------------------------------------------------------

DROP TABLE IF EXISTS `smallurl_wot`;

CREATE TABLE `smallurl_wot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `smallurl` text NOT NULL,
  `reputation` int(11) NOT NULL,
  `stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
