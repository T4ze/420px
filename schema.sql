use 420px;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `owner` int(11) NOT NULL REFERENCES `users` (`id`),
    `path` varchar(255) NOT NULL,
    `color_1` varchar(7) DEFAULT '#FFFFFF',
    `color_2` varchar(7) DEFAULT '#FFFFFF',
    `color_3` varchar(7) DEFAULT '#FFFFFF',
    `color_4` varchar(7) DEFAULT '#FFFFFF',
    `color_5` varchar(7) DEFAULT '#FFFFFF',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);