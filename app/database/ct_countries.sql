CREATE TABLE `ct_countries` (
  `id_country` smallint(5) UNSIGNED NOT NULL,
  `id_continent` tinyint(5) UNSIGNED NOT NULL,
  `en` varchar(40) NOT NULL,
  `es` varchar(40) NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ct_countries` (`id_country`, `id_continent`, `en`, `es`, `active`) VALUES
(1, 1, 'Albanian', 'Albania', 0),
(2, 1, 'Germany', 'Alemania', 0),
(3, 1, 'Andorra', 'Andorra', 0),
(4, 1, 'Armenia', 'Armenia', 0),
(5, 1, 'Austria', 'Austria', 0),
(6, 1, 'Azerbaijan', 'Azerbaiyán', 0),
(7, 1, 'Belgium', 'Bélgica', 0),
(8, 1, 'Belarus', 'Bielorrusia', 0),
(9, 1, 'Bosnia and Herzegovina', 'Bosnia y Herzegovina', 0),
(10, 1, 'Bulgaria', 'Bulgaria', 0),
(11, 1, 'Cyprus', 'Chipre', 0),
(12, 1, 'Croatia', 'Croacia', 0),
(13, 1, 'Denmark', 'Dinamarca', 0),
(14, 1, 'Slovakia', 'Eslovaquia', 0),
(15, 1, 'Slovenia', 'Eslovenia', 0),
(16, 1, 'Spain', 'España', 1),
(17, 1, 'Estonia', 'Estonia', 0),
(18, 1, 'Finland', 'Finlandia', 0),
(19, 1, 'France', 'Francia', 0),
(20, 1, 'Georgia', 'Georgia', 0),
(21, 1, 'Greece', 'Grecia', 0),
(22, 1, 'Hungary', 'Hungría', 0),
(23, 1, 'Ireland', 'Irlanda', 0),
(24, 1, 'Iceland', 'Islandia', 0),
(25, 1, 'Italy', 'Italia', 0),
(26, 1, 'Latvia', 'Letonia', 0),
(27, 1, 'Liechtenstein', 'Liechtenstein', 0),
(28, 1, 'Lithuania', 'Lituania', 0),
(29, 1, 'Luxembourg', 'Luxemburgo', 0),
(30, 1, 'North Macedonia', 'Macedonia del Norte', 0),
(31, 1, 'Malta', 'Malta', 0),
(32, 1, 'Moldova', 'Moldavia', 0),
(33, 1, 'Monaco', 'Mónaco', 0),
(34, 1, 'Montenegro', 'Montenegro', 0),
(35, 1, 'Norway', 'Noruega', 0),
(36, 1, 'Netherlands', 'Países Bajos', 0),
(37, 1, 'Poland', 'Polonia', 0),
(38, 1, 'Portugal', 'Portugal', 0),
(39, 1, 'United Kingdom', 'Reino Unido', 0),
(40, 1, 'Czech Republic', 'República Checa', 0),
(41, 1, 'Romania', 'Rumania', 0),
(42, 1, 'Russia', 'Rusia', 0),
(43, 1, 'San Marino', 'San Marino', 0),
(44, 1, 'Serbian', 'Serbia', 0),
(45, 1, 'Sweden', 'Suecia', 0),
(46, 1, 'Swiss', 'Suiza', 0),
(47, 1, 'Turkey', 'Turquía', 0),
(48, 1, 'Ukraine', 'Ucrania', 0),
(49, 1, 'Vatican', 'Vaticano', 0);

ALTER TABLE `ct_countries`
  ADD PRIMARY KEY (`id_country`);

ALTER TABLE `ct_countries`
  MODIFY `id_country` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;