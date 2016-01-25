<?php
require_once '../load.php';
if( isset($_POST['garbage_log']) ){
    $item = $_POST['garbage_log'];
    $sql = "SELECT * FROM error_garbage WHERE domain = '$item'";
    $result_check = $con->customQuery($sql);
    if($result_check->num_rows == 1){
        $new_sql = "DELETE FROM error_garbage WHERE domain = '$item'";
    }
    else{
        $new_sql = "INSERT INTO error_garbage SET domain = '$item'";
    }
    $action_result = $con->customQuery($new_sql);
    if($action_result){
        echo 'success';
    }
    else{
        echo $con->mysqli_error();
    }
}
?>