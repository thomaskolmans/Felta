CREATE TABLE IF NOT EXISTS `newsletter`(
    `id` varchar(32),
    `name` varchar(556),
    `description` text,
    `createdAt` DateTime,
    `updatedAt` DateTime
);
CREATE TABLE IF NOT EXISTS `newsletter_message`(
    `id` varchar(32),
    `title` varchar(556),
    `author` varchar(255),
    `description` text,
    `body` longtext,
    `sendAt` DateTime,
    `createdAt` DateTime,
    `updatedAt` DateTime
);
CREATE TABLE IF NOT EXISTS `newsletter_subscription`(
    `id` int auto_increment PRIMARY KEY, 
    `newsletter` varchar
    `email` varchar(255),
    `date` datetime
);