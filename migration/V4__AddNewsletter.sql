CREATE TABLE IF NOT EXISTS `newsletter`(

);
CREATE TABLE IF NOT EXISTS `newsletter_message`(
    "id" => "varchar(32)",
    "title" => "varchar(556)",
    "author" => "varchar(255)",
    "description" => "text",
    "body" => "longtext",
    "sendAt" => "DateTime",
    "createdAt" => "DateTime",
    "updatedAt" => "DateTime"
);
CREATE TABLE IF NOT EXISTS `newsletter_subscription`(
    `id` int auto_increment PRIMARY KEY, 
    `email` varchar(255),
    `date` datetime
);