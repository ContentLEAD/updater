<?php
require_once 'load.php';
echo '<pre>';
var_dump($_GET);
echo '</pre>';
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $function = $_GET['function'];
}
include($plugin.'/'.$function.'.php');
?>