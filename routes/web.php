<?php
use lib\Routing\Route;

Route::get("","index.tpl")->primary();
Route::get("/about","about.tpl");
Route::get("/agenda","agenda.tpl");
Route::get("/berrie","berrie.tpl");
Route::get("/date","date.tpl");
Route::get("/contact","contact.tpl");
?>