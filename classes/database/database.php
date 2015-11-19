<?php
include_once BASE_PATH.'classes/error_test.php';
include_once BASE_PATH. 'classes/arrayutils.php';
require_once BASE_PATH.'classes/database/connect.php';
require_once BASE_PATH.'classes/database/datastructure.php';

foreach(glob(BASE_PATH."classes/database/tables/*_table.php") as $filename){
    include_once $filename;
    $file = explode('.',basename($filename));
    if(class_exists($file[0])){
        $instantiate = new $file[0]();
    }
}