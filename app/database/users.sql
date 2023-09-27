CREATE TABLE `users` (
  `id_user` mediumint(8) UNSIGNED NOT NULL,
  `email` varchar(90) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `name` varchar(90) NOT NULL,
  `lastname` varchar(120) NOT NULL,
  `remember_code` varchar(50) NOT NULL DEFAULT '',
  `validation_code` varchar(50) NOT NULL,
  `validated_email` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `last_access` datetime DEFAULT NULL,
  `insert_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_register` varchar(20) NOT NULL DEFAULT '',
  `ip_last_access` varchar(20) NOT NULL DEFAULT '',
  `id_state` tinyint(3) UNSIGNED NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `id_user` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;