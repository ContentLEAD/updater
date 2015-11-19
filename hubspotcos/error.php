<?php
if(isset($_REQUEST['key'])){
    $key = $_REQUEST['key'];
    if($results = $con->retrieveData('keys','*', array('WHERE'=> array("encryptionKey='$key'")))){
        //log special error key invalid
        echo '<br>Logging Error Report<br>';
        $type = $results[0]['importer'];
        $error = $_REQUEST['error'];
        $url = json_decode($error)->Domain;
        $data = array(
            'type'          => $type,
            'date'          => $date,
            'domain'        => $url,
            'error'         => $error
        );
        echo '<pre>';
        var_dump($data);
        $con->insertData('errors',$data);
    }
    else{
       echo 'Encryption key Not Found';
        
    }
}else{
    //log special error.  Something attempted to access this page without proper encryption key
    echo 'no encryption key';
}
?>