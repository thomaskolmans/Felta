CREATE TABLE IF NOT EXISTS `promotion` (
    `id` varchar(255),
    `name` varchar(255),
    `code` varchar(255),
    `percentage` FLOAT(10),
    `amount` int,
    `startsAt` datetime,
    `endsAt` datetime,
    `createdAt` datetime,
    `updatedAt` datetime
);

ALTER TABLE `shop_product` ADD COLUMN `isDownloadable` varchar(255) AFTER `category`;