<?php
namespace lib\helpers;

use lib\Felta;

class FormValidator extends Validator{

    public function __construct($items,$memory = false){

        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){

                $value = Input::get($item);

                if($rule == "required" && empty($value)){
                    $this->addError($item." is required!");
                    return false;

                }else if(!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError($item." must be a minimum of ".$rule_value);    
                                return false;
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError($item." must be a maximum of ".$rule_value);    
                                return false;
                            }
                        break;
                        case 'matches':
                            if($value != Input::get($rule_value)){
                                $this->addError($item." must match ".$rule_value);  
                                return false;
                            }
                        break;
                        case 'has':
                            switch($rule_value){
                                case'uppercase':
                                    if(!preg_match("/[A-Z]/", $value)){
                                        $this->addError($item." must have a ".$rule_value);
                                        return false;
                                    }
                                break;
                                case'lowercase':
                                    if(!preg_match("/[a-z]/", $value)){
                                        $this->addError($item." must have a ".$rule_value);
                                        return false;
                                    }
                                break;
                                case 'number':
                                    if(preg_match("/[0-9]/",$value)){
                                        $this->addError($item." must have a ".$rule_value);
                                        return false;
                                    }
                                break;
                                case 'symbol':
                                    if (preg_match('/[^A-Za-z].*[0-9]|[0-9].*[A-Za-z]+/', $value) == 0){
                                        $this->addError($item." must have a ".$rule_value);
                                        return false;
                                    }                                   
                                break;
                            }
                        break;
                        case 'unique':
                            if(Felta::getInstance()->getSQL()->exists($rule_value,array($item => $value))){
                                $this->addError($item." is already in use");
                                return false;
                            }
                        break;  
                    }
                }
            }
        }
        if(empty($this->errors)){
            $this->setPassed(true);
            return true;
        }
    }
    
    public function addError($error){
        array_push($this->errors,$error);
    }
    public function getError($number){
        return $this->errors[$number];
    }
    public function getErrors(){
        return $this->errors;
    }
    public function setPassed($passed){
        $this->passed = $passed;
        return;
    }
    public function isPassed(){
        return $this->passed;
    }
}
?>