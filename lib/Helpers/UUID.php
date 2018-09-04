<?php
namespace lib\Helpers;

class UUID {

    public static function generate(){
        return uniqid();
    }
}
?>