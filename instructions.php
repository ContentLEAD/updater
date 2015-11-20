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
                <?php get_header('Development Instructions', 'Usage Outside Live Enviorment'); ?>
                    <div class="install-instructions">
                    <h3>Welcome to the Brafton Update &amp; Error API Installation Wizard</h3>
                    <p>This application provides an interface for the various CMS's to report Errors from our library of importers as well as utilize the built in upgrading features of that CMS to keep our products up to date. Following the instructions below you will be able install this application as either a Development, Staging, or Prodution Enviorment.</p>
                    <p>If installing on your local system to debug or work out new features, install a <i>Development</i> Enviorment.  Be sure that you are running this installation using the <i>Development-{yourname}</i> Git Branch. After correcting the bugs or implimenting the new features on your <i>Development</i> Enviorment Pull an updated <i>Development, and Staging</i> Branch. Merge locally your <i>Development-{yourname}</i> Git Branch into your Updated <i>Development</i> Branch.  Correct any Merge Conflicts, if any, and merge locally your <i>Development</i> into your <i>Staging</i> Branch.</p>
                    <p>If your final merge results in conflicts, afte you have resolved them you must merge your new local <i>Staging</i> Branch into your local <i>Development</i> Branch.  Only than are you free to push your <i>Development</i> Branch to Git. </p>
                        <p>You may now push your local <i>Development</i> Branch to Git and using the Git UI merge into remote <i>Staging</i> Branch.</p>
                    </div>
            </section>
                
        </div>
    </body>
</html>