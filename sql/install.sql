CREATE TABLE IF NOT EXISTS `mc_textblock` (
    `id_tb` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `alias` varchar(100) NOT NULL COMMENT 'Clé unique pour Smarty (ex: intro_home, footer_promo)',
    `context` varchar(50) DEFAULT 'home' COMMENT 'Module ou page de destination (ex: home, news)',
    `date_register` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id_tb`),
    UNIQUE KEY `alias` (`alias`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mc_textblock_content` (
    `id_content` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_tb` int(10) UNSIGNED NOT NULL,
    `id_lang` smallint(3) UNSIGNED NOT NULL,
    `content_tb` text,
    `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id_content`),
    KEY `id_tb` (`id_tb`),
    KEY `id_lang` (`id_lang`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `mc_textblock_content`
    ADD CONSTRAINT `mc_textblock_content_ibfk_1` FOREIGN KEY (`id_tb`) REFERENCES `mc_textblock` (`id_tb`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_textblock_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;