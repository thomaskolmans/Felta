CREATE TABLE IF NOT EXISTS `felta` (
    `id` int auto_increment PRIMARY KEY,
    `online` boolean,
    `date` DateTime,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS `user` (
    `id` int auto_increment PRIMARY KEY,
    `username` varchar(255),
    `dname` varchar(255),
    `password` varchar(550),
    `email` varchar(255),
    `online` boolean,
    `active` boolean
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS `user_remember` (
    `id` int PRIMARY KEY,
    `key` varchar(255),
    `start` DateTime,
    `expire` DateTime
);

CREATE TABLE IF NOT EXISTS `user_history` (
    
);