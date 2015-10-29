<?php
require 'classes/connect.php';
require 'inc/utils.php';
if(isset($_REQUEST['key'])){
    $key = $_REQUEST['key'];
    $keycheck = new DBConnect();
    if($results = $keycheck->retrieveData('keys','*', array('WHERE'=> array("encryptionKey='$key'")))){
        //log special error key invalid
        echo '<br>Logging Error Report<br>';
        $type = $results[0]['importer'];
        $error = $_REQUEST['error'];
        $data = array(
            'type'          => $type,
            'date'          => $date,
            'error'         => $error
        );
        echo '<pre>';
        var_dump($data);
        $logError = new DBConnect();
        $logError->insertData('errors',$data);
    }
    else{
       echo 'Encryption key Not Found';
        
    }
}else{
    //log special error.  Something attempted to access this page without proper encryption key
    echo 'no encryption key';
}
?>