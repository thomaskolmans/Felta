<?php
namespace lib\Filesystem;

class Stream{

    public $file;

    public function __construct($file){
        $this->file = $file;
    }
}