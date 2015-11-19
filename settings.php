<?php 
require_once 'load.php';
$action = 'inc/hold.php';
if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action == 'wp-remote'){
        $action = 'wp-remote/interface.php';
    }else{
        $action = 'inc/'.$action.'.php';
    }
}
$action = BASE_PATH . $action;
if(isset($_POST['act'])){
    
    switch ($_POST['act']){
        case 'add':
        new_importer();
        break;
        case 'update':
        update_importer();
        break;
        case 'edit':
        edit_importer();
        break;
        case 'key':
        get_error_key();
        break;
        default:
        error_importer();
        break;
    }  
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control</title>
        <link rel="stylesheet" href="css/style.css">
        <!--Jquery from google cdn-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="js/brafton.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="body">
            <head>
               <?php get_header('Brafton Plugin and Module Settings'); ?>
            </head>
            <section>
                <nav class="settings-options">
                    <ul>
                        <li><a href="?action=add">Add New Plugin</a></li>
                        <li><a href="?action=update">Register Plugin Update</a></li>
                        <li><a href="?action=edit">Edit Exsisting Entry</a></li>
                        <li><a href="?action=key">Auto Update key</a></li>
                        <li><a href="?action=codename">Activate Version Name</a></li>
                    </ul>
                </nav>
                <form action="" method="post" onsubmit="return check_vals()" >
                    <?php include $action; ?>
                </form>
            </section>
        </div>
    </body>
</html>