CREATE TABLE IF NOT EXISTS `article_image`(
    `id` varchar(255) PRIMARY KEY,
    `article` varchar(255),
    `url` varchar(1024),
    `description` text,
    `createdAt` DateTime
);