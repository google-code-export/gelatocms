CREATE TABLE IF NOT EXISTS `comments` (
  `id_comment` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(250) DEFAULT NULL,
  `content` text NOT NULL,
  `ip_user` varchar(50) NOT NULL,
  `comment_date` datetime NOT NULL,
  `spam` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id_comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(3) NOT NULL,
  `posts_limit` int(10) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `lang` varchar(10) NOT NULL,
  `template` varchar(100) NOT NULL,
  `url_installation` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `configurations` (`id`, `posts_limit`, `title`, `description`, `lang`, `template`, `url_installation`, `created`, `modified`) VALUES
(1, 10, 'Gelato CMS', 'Gelato flavored version', 'en', 'tumblr', 'http://localhost/gelato', '2009-08-06 01:47:45', '2009-08-06 01:47:45');

CREATE TABLE IF NOT EXISTS `entries` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `title` text,
  `url` varchar(250) DEFAULT NULL,
  `description` text,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `date` datetime NOT NULL,
  `id_user` int(10) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id_post`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `entries` (`id_post`, `title`, `url`, `description`, `type`, `date`, `id_user`, `modified`, `created`) VALUES
(1, 'The gelato project ', '', 'Is open source (you can use and change the original source code freely).', 1, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(2, '', 'muestra.jpg', 'Esto es una foto de Victor...', 2, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(3, '', 'http://www.youtube.com/watch?v=-hFUo9PLFnE', 'Una camioneta en llamas en el crucero de Carrizalillos y La Maria.\r\n\r\nLa foto se ve mejor que el video:\r\n\r\n<a href="http://www.flickr.com/photos/victorrocha/3784416674/">http://www.flickr.com/photos/victorrocha/3784416674/</a>', 6, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(4, '', 'http://www.goear.com/listen.php?v=c0a2c85', 'Camila - Coleccionista de Canciones ', 7, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(5, 'Mis Algoritmos', 'http://mis-algoritmos.com', '', 4, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(6, 'http://twitter.com/GiBraiNe/status/166151912', '', 'Las palabras son huecas mientras no existan acciones que las rellenen.', 3, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
(7, 'The geek-normal conversation :)', '', 'Geek: Wow an open source tumblelog CMS!!.\r\nNormal: Tumble... what??\r\nGeek: Read the wikipedia!! ', 5, '2009-08-06 01:47:45', 1, '2009-08-06 01:47:45', '2009-08-06 01:47:45');

CREATE TABLE IF NOT EXISTS `feeds` (
  `id_feed` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `updated_at` datetime NOT NULL,
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `credits` int(1) NOT NULL DEFAULT '0',
  `site_url` varchar(255) NOT NULL,
  `id_user` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id_feed`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `options` (
  `name` varchar(100) NOT NULL,
  `val` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `options` (`name`, `val`, `created`, `modified`) VALUES
('rich_text', '0', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('allow_comments', '0', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('offset_city', 'Mexico/General', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('offset_time', '-6', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('shorten_links', '0', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('rss_import_frec', '5 minutes', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('check_version', '1', '2009-08-06 01:47:45', '2009-08-06 01:47:45'),
('active_plugins', '[{"total":0},[]]', '2009-08-06 01:47:45', '2009-08-06 01:47:45');

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `login` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `about` text,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id_user`, `name`, `login`, `password`, `email`, `website`, `about`, `created`, `modified`) VALUES
(1, 'Gelater', 'admin', md5('demo'), 'contacto@example.com', 'http://www.gelatocms.com/', '', '2009-08-06 01:47:45', '2009-08-06 01:47:45');
