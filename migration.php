<?php
use lib\SimpleSQl;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("./autoloader.php");
require_once('./vendor/nytrix/simplesql/autoloader.php');
require_once('./vendor/autoload.php');

$sql = new SimpleSQL();
$sql->migration->clean();
$sql->migration->migrate();

?>