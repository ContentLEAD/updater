<?php 
require_once 'load.php';
$action = 'inc/hold.php';
if(isset($_GET['action'])){
    $action = $_GET['action'];
    $subTitle = $action;
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
if(isset($_SESSION['previousAction'])){
    $previousAction = $_SESSION['previousAction'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control</title>
        <?php include_once BASE_PATH.'inc/scripts.php'; ?>
    </head>
    <body class="page-<?php echo $page_name; ?>">
        <header class="top">
                <?php include BASE_PATH.'inc/nav.php'; ?>
            </header>
        <div class="body">
            <section class="main-container">
                <?php get_header('Brafton Plugin and Module Settings', (isset($subTitle)? strtoupper( $subTitle): null) ); ?>
                <nav class="settings-options">
                    <ul>
                        <li><a href="?action=add">Add New Plugin</a></li>
                        <li><a href="?action=update">Register Plugin Update</a></li>
                        <li><a href="?action=edit">Edit Exsisting Entry</a></li>
                        <li><a href="?action=key">Access key</a></li>
                        <li><a href="?action=codename">Activate Version Name</a></li>
                    </ul>
                </nav>
                <form action="" method="post" onsubmit="return check_vals()" class="administration-settings-form" enctype="multipart/form-data">
                    <?php if(!isset($_GET['action'])){ ?>
                    <h2><i class="fa fa-hand-o-left"></i>Please select an action from the menu</h2>
                    <?php } ?>
                    <?php if(isset($previousAction)){ ?>
                        <h2>You have successfully <?php echo $previousAction; ?></h2>
                    <?php } ?>
                    <?php include $action; ?>
                </form>
            </section>
                
        </div>
    </body>
</html>