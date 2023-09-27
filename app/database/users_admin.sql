CREATE TABLE `users_admin` (
  `id_admin` tinyint(3) UNSIGNED NOT NULL,
  `email` varchar(90) NOT NULL,
  `pass` varchar(90) NOT NULL,
  `name` varchar(90) NOT NULL,
  `remember_code` varchar(50) NOT NULL DEFAULT '',
  `id_admin_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Privilege level',
  `last_access` datetime DEFAULT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_last_access` varchar(20) NOT NULL DEFAULT '',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users_admin`
  MODIFY `id_admin` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;