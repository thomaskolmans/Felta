<?php
use lib\Routing\Route;
use lib\Felta;

if(json_decode(Felta::getInstance()->getStatus())->online){
    Route::get("/","index.tpl")->primary(); 
}else{
    Route::get("/","felta/maintenance.tpl")->primary();
}

?>