<?php

namespace lib\helpers;

abstract class Validator{

    public $errors = [];
    public $passed = false;
    abstract function addError($error);
    abstract function getError($number);
    abstract function setPassed($passed);
    abstract function isPassed();
}
?>