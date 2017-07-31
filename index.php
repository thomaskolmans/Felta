<?php
use lib\Felta;
use lib\SimpelSQL;
use lib\Cleverload;

require_once("autoloader.php");
require_once("lib\Cleverload\autoloader.php");
require_once("lib\SimpelSQL\autoloader.php");

$sql = new SimpelSQL();
$felta = new Felta($sql);
$cleverload = new Cleverload();

?> 