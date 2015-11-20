<?php 
if(!defined("BASE_PATH")){
    define("BASE_PATH", realpath(dirname("../load.php")).'/');
    define("DEBUG_MODE", false);
}
include_once BASE_PATH . 'inc/utils.php';
if(isset($_POST['HOST'])){
    include_once BASE_PATH . 'classes/database/_creds.php';
}
if(file_exists(BASE_PATH.'classes/database/creds.php')){
    if(!isset($_GET['install'])){
        header("LOCATION:/");
    }
    require_once BASE_PATH.'classes/database/database.php';
    $query = file_get_contents(BASE_PATH.'sql/TestData.sql');
    $installation = $con->uploadDB($query);
    $Ierrors = $installation[0];
    $IerrorList = $installation[1];
}
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8">
    <title>Brafton Update &amp; Error API Installation</title>
	<meta name="Author" content=""/>
    <?php include_once BASE_PATH.'inc/scripts.php'; ?>
</head>
<body>
    <header class="top">
                <?php include BASE_PATH.'inc/nav.php'; ?>
            </header>
        <div class="body">
            <section class="main-container">
               <?php get_header('Brafton Plugin and Module Version Control', 'Installation Wizard'); ?>
                <div class="install-instructions">
                    <h3>Welcome to the Brafton Update &amp; Error API Installation Wizard</h3>
                    <p>This application provides an interface for the various CMS's to report Errors from our library of importers as well as utilize the built in upgrading features of that CMS to keep our products up to date. Following the instructions below you will be able install this application as either a Development, Staging, or Prodution Enviorment.</p>
                    <p>If installing on your local system to debug or work out new features, install a <i>Development</i> Enviorment.  Be sure that you are running this installation using the <i>Development-{yourname}</i> Git Branch. After correcting the bugs or implimenting the new features on your <i>Development</i> Enviorment Pull an updated <i>Development, and Staging</i> Branch. Merge locally your <i>Development-{yourname}</i> Git Branch into your Updated <i>Development</i> Branch.  Correct any Merge Conflicts, if any, and merge locally your <i>Development</i> into your <i>Staging</i> Branch.</p>
                    <p>If your final merge results in conflicts, afte you have resolved them you must merge your new local <i>Staging</i> Branch into your local <i>Development</i> Branch.  Only than are you free to push your <i>Development</i> Branch to Git. </p>
                        <p>You may now push your local <i>Development</i> Branch to Git and using the Git UI merge into remote <i>Staging</i> Branch.</p>
                    </div>
                    <form class="installation-form" action="<?php echo $_SERVER['PHP_SELF']; ?>?install" method="post" onsubmit="return check_vals();">
                        <?php if(!isset($Ierrors)){ ?>
                        <h2>MySQL Database Credentials</h2>
                        <label>
                            <span class="env-hind" title="NOTE:
                              Only choose Staging or Enviorment if deploying
                              to a server enviorment.  An instance of each of 
                              these exists on the Tech Server in the ROOT HTMl
                              folder"><i class="fa fa-exclamation-triangle"></i>Enviorment</span>
                            <select name="ENVIORMENT">
                                <option value="">Select Enviorment</option>
                                <option value="DEVELOPMENT">Development</option>
                                <option value="STAGING">Staging</option>
                                <option value="LIVE">Production</option>
                            </select>
                        </label>
                        <label>
                            <span>Host</span>
                            <input type="text" name="HOST" placeholder="localhost">
                        </label>
                        <label>
                            <span>Database Name</span>
                            <input type="text" name="DATABASE" placeholder="update_db">
                        </label>
                        <label>
                            <span>User Name</span>
                            <input type="text" name="USER" placeholder="update_db_user">
                        </label>
                        <label>
                            <span>Password</span>
                            <input type="text" name="PASSWORD" placeholder="password">
                        </label>
                        <p>Currently this application only supports a MySQL Database configuration.</p>
                        <input type="submit" value="Install">
                        <?php }else if(!$Ierrors){ ?>
                            <label style="width:100%;">
                                <h2 class="success">You have successfully Installed your Application</h2>
                            </label>
                        <?php }else{ ?>
                            <h2 class="failure">There appears to have been <?php echo $Ierrors; ?> errors with your installation.  Please see the error log below.</h2>
                            <?php foreach($IerrorList as $error){ ?>
                                <pre class="datastructure error">
                                    <?php echo $error; ?>
                                </pre>
                            <?php } ?>
                        <?php } ?>
                    </form>
            </section>
        </div>
    <pre>
    </pre>
</body>
</html>
