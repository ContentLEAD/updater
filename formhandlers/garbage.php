<?php
require_once '../load.php';
if( isset($_POST['garbage']) ){
    $garbage = json_decode($_POST['garbage']);
    $garbage = implode("\",\"", $garbage);
    $delete = 'DELETE FROM errors WHERE domain in ("'.$garbage.'")';
    $result_delete = $con->customQuery($delete);
    if($result_delete){
        echo 'success';
    }
    else{
        echo $con->mysqli_error();
    }
}
?>