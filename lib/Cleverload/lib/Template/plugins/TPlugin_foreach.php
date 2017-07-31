<?php
namespace lib\Template\plugins;

use lib\Template\TemplatePlugin;

class TPlugin_foreach extends TemplatePlugin{

    public $arguments;

    public function __construct($arguments){
        $this->arguments = $arguments;
    }
}
?>
