<?php
define("BASE_PATH", dirname(realpath(__FILE__)).'/');
define("DEBUG_MODE", TRUE);
if(file_exists(BASE_PATH .'classes/creds.php')){
    rename(BASE_PATH .'classes/creds.php', BASE_PATH .'classes/database/creds.php');
}
if(!file_exists(BASE_PATH .'classes/database/creds.php')){
    header('LOCATION:/install/');
}
include_once BASE_PATH .'inc/utils.php';