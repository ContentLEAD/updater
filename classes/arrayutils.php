<?php
function __autoload($class_name){
    include_once 'classes/database/tables/'.$class_name . '.php';
}
class ArrayUtils{
    public static function objArraySearch($array,$index,$find){
                foreach($array as $key => $value) {
                    if($value->{$index} == $find){
                        return $key;
                    }
                }
                return null;
            }
}