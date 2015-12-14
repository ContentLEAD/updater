<?php 
$errors = $_POST['errors'];
$filename = $_POST['filename'];
$client = $_GET['client'];
if(!is_dir('logs/'.$client)){
    mkdir('logs/'.$client);
    chmod('logs/'.$client, 0777);
}
$file = fopen('logs/'.$client.'/'.$filename, 'w');
if(fwrite($file, $errors)){
    fclose($file);
    echo 'success';
}else{
    echo 'failure';
    var_dump($_POST);
}