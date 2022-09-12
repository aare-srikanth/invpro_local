/*CREATE TABLE IF NOT EXISTS `#__boxon_register` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;
*/
ALTER TABLE `#__users` ADD `webserviceid` INT(11) NOT NULL AFTER `requireReset`;