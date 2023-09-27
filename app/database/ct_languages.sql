CREATE TABLE `ct_languages` (
  `id_language` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(90) NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ct_languages` (`id_language`, `name`, `active`) VALUES
(1, 'en', 1),
(2, 'es', 1),
(3, 'fr', 0);

ALTER TABLE `ct_languages`
  ADD PRIMARY KEY (`id_language`);

ALTER TABLE `ct_languages`
  MODIFY `id_language` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;