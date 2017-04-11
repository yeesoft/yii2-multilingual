DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `post_lang`;

CREATE TABLE `post_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `language` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_post_lang` (`owner_id`),
  CONSTRAINT `fk_post_lang` FOREIGN KEY (`owner_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `post_advanced`;

CREATE TABLE `post_advanced` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `post_advanced_language`;

CREATE TABLE `post_advanced_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `lang` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_post_advanced_lang` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `post_prefixed`;

CREATE TABLE `post_prefixed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `post_prefixed_lang`;

CREATE TABLE `post_prefixed_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `language` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `val_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `val_content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `page_lang`;

CREATE TABLE `page_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `language` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_page_lang` (`owner_id`),
  CONSTRAINT `fk_page_lang` FOREIGN KEY (`owner_id`) REFERENCES `page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `post` VALUES (1,'title');
INSERT INTO `post_lang` VALUES (1,1,'en-US','EN-US Title','EN-US Content');
INSERT INTO `post_lang` VALUES (2,1,'es','ES Title','ES Content');

INSERT INTO `page` VALUES (1,'title');
INSERT INTO `page` VALUES (2,'second');
INSERT INTO `page_lang` VALUES (1,1,'en-US','EN-US Title','EN-US Content');
INSERT INTO `page_lang` VALUES (2,1,'es','ES Title','ES Content');
INSERT INTO `page_lang` VALUES (3,1,'pt-BR','PT Title','PT Content');
INSERT INTO `page_lang` VALUES (4,2,'en-US','Second EN-US Title','Second EN-US Content');
INSERT INTO `page_lang` VALUES (5,2,'es','Second ES Title','Second ES Content');
INSERT INTO `page_lang` VALUES (6,2,'pt-BR','Second PT Title','Second PT Content');

INSERT INTO `image` VALUES (1,'http://www.mysite.com/image.jpg');
