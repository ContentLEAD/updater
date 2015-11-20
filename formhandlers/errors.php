<?php
require_once '../load.php';
$con = new DBConnect();
if(isset($_POST['deleting_domain']) && $_POST['deleting_domain'] == 'submition'){
    $error_domains = implode('","',$_POST['domain-select']);
    $delete = 'DELETE FROM errors WHERE domain in ("'.$error_domains.'")';
    $result_delete = $con->customQuery($delete);
    var_dump($result_delete);
}
?>