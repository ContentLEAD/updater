<?php 
require_once 'load.php';
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
                <?php get_header('Brafton Remotes'); ?>
                    <?php include 'wp-remote/interface.php'; ?>
            </section>
                
        </div>
    </body>
</html>