CREATE TABLE `categories_language` (
  `id_category` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(999) NOT NULL,
  `meta_keywords` varchar(999) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `categories_language`
  ADD PRIMARY KEY (`id_category`);
COMMIT;