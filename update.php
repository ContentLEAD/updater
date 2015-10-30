<?php
error_reporting(E_ALL);
init_set("display_errors", 1);
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $function = $_GET['function'];
}
include($plugin.'/'.$function.'.php');
?>