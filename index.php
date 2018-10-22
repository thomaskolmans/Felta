<?php
use lib\Felta;
use lib\SimpleSQL;
use lib\Cleverload;
use lib\Http\Request;
use lib\Routing\Router;
use lib\Shop\Shop;

require_once("autoloader.php");
require_once("lib/Cleverload/autoloader.php");
require_once("lib/SimpleSQL/autoloader.php");
require_once('lib/Shop/stripe/init.php');
require_once('lib/Shop/paypal/autoload.php');

$sql = new SimpleSQL();
$felta = new Felta($sql);

$shop = Shop::create("test","sk_test_g9Z0TfcCUzQLAB3GPEJG7cYK","");

$request = new Request($_SERVER);
$cleverload = new Cleverload($request);
$cleverload->setStaticFilesDir("./view/");
$cleverload->setViewDir("./view/");

//$cleverload->forceHttps();
$cleverload->getRequest()->getRouter()->getResponse();

?> 