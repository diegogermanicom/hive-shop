CREATE TABLE `ct_continents` (
  `id_continent` tinyint(5) UNSIGNED NOT NULL,
  `en` varchar(40) NOT NULL,
  `es` varchar(40) NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ct_continents` (`id_continent`, `en`, `es`, `active`) VALUES
(1, 'Europe', 'Europa', 1),
(2, 'America', 'America', 0),
(3, 'Asia', 'Asia', 0),
(4, 'Africa', 'Africa', 0),
(5, 'Oceanía', 'Oceanía', 0);

ALTER TABLE `ct_continents`
  ADD PRIMARY KEY (`id_continent`);

ALTER TABLE `ct_continents`
  MODIFY `id_continent` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;