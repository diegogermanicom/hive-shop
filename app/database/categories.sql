CREATE TABLE `categories` (
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `id_parent` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `view` varchar(255) NOT NULL,
  `active` tinyint(3) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

ALTER TABLE `categories`
  MODIFY `id_category` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;