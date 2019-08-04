<?php
use lib\Felta;
use lib\SimpleSQL;
use lib\Cleverload;
use lib\Http\Request;
use lib\Routing\Router;
use lib\Shop\Shop;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("./autoloader.php");
require_once('./vendor/nytrix/cleverload/autoloader.php');
require_once('./vendor/nytrix/simplesql/autoloader.php');
require_once('./vendor/autoload.php');

$sql = new SimpleSQL();
$felta = new Felta($sql);

$shop = Shop::create("test");

$request = new Request($_SERVER);
$cleverload = new Cleverload($request);
$cleverload->setStaticFilesDir("./view/");
$cleverload->setViewDir("./view/");


//$cleverload->forceHttps();
$cleverload->getRequest()->getRouter()->getResponse();

?> 