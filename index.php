<?php
use lib\Felta;
use lib\SimpelSQL;
use lib\Cleverload;
use lib\Http\Request;
use lib\Routing\Router;

require_once("autoloader.php");
require_once("lib/Cleverload/autoloader.php");
require_once("lib/SimpelSQL/autoloader.php");

DEFINE('ROOT',dirname(__FILE__));

$sql = new SimpelSQL();
$felta = new Felta($sql);

$request = new Request($_SERVER);
$cleverload = new Cleverload($request);
$cleverload->setStaticFilesDir("./view/");
$cleverload->setViewDir("./view/");
$cleverload->getRequest()->getRouter()->getResponse();

?> 