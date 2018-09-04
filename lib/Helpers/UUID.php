<?php
namespace lib\Helpers;

class UUID{

    public static function generate($bytes){
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }
}
?>