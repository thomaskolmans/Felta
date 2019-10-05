<?php

use lib\SimpleSQL;

require_once("./autoloader.php");
require_once('./vendor/nytrix/simplesql/autoloader.php');
require_once('./vendor/autoload.php');

$sql = new SimpleSQL();

$sql->execute("DROP TABLE `blog`");
$sql->execute("DROP TABLE `blog_article`");
$sql->execute("DROP TABLE `blog_comment`");
$sql->execute("DROP TABLE `blog_like`");

$sql->create("blog",[
    "id" => "varchar(32)",
    "name" => "varchar(556)",
    "description" => "text",
    "active" => "boolean",
    "order" => "int",
    "createdAt" => "DateTime",
    "updatedAt" => "DateTime"
], "id");

$sql->create("article", [
    "id" => "varchar(32)",
    "blog" => "varchar(32)",
    "title" => "varchar(556)",
    "author" => "varchar(255)",
    "description" => "text",
    "body" => "longtext",
    "active" => "boolean",
    "activeFrom" => "DateTime",
    "createdAt" => "DateTime",
    "updatedAt" => "DateTime"
], "id");
$sql->create("comment", [
    "id" => "varchar(32)",
    "parent" => "varchar(32)",
    "article" => "varchar(32)",
    "user" => "varchar(32)",
    "name" => "varchar(255)",
    "comment" => "longtext",
    "accepted" => "boolean",
    "createdAt" => "DateTime",
    "updatedAt" => "DateTime"
], "id");

$sql->create("`like`", [
    "id" => "varchar(32)",
    "parent" => "varchar(32)",
    "namespace" => "varchar(255)",
    "user" => "varchar(32)",
    "name" => "varchar(255)",
    "type" => "int",
    "createdAt" => "DateTime",
    "updatedAt" => "DateTime"
], "id");