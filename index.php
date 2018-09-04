<?php
use lib\Felta;
use lib\SimpleSQL;
use lib\Cleverload;
use lib\Http\Request;
use lib\Routing\Router;

require_once("autoloader.php");
require_once("lib/Cleverload/autoloader.php");
require_once("lib/SimpleSQL/autoloader.php");

$sql = new SimpleSQL();
$felta = new Felta($sql);

$request = new Request($_SERVER);
$cleverload = new Cleverload($request);
$cleverload->setStaticFilesDir("./view/");
$cleverload->setViewDir("./view/");
//$cleverload->forceHttps();
$cleverload->getRequest()->getRouter()->getResponse();

?> 