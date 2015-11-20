<?php
require_once 'load.php';
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $function = $_GET['function'];
}
include($plugin.'/'.$function.'.php');
?>