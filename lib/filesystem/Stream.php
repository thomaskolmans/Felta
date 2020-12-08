<?php

namespace lib\filesystem;

class Stream
{

    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }
}
