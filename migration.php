<?php
use lib\SimpleSQL;

require_once("./autoloader.php");
require_once('./vendor/nytrix/simplesql/autoloader.php');
require_once('./vendor/autoload.php');

$sql = new SimpleSQL();
$sql->migration->clean();
$sql->migration->migrate();

?>