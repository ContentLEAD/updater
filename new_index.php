<?php 
require_once 'load.php';
$system = $con->retrieveData('systems','*');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control STAGING</title>
        <?php include_once BASE_PATH.'inc/scripts.php'; ?>
    </head>
    <body class="page-<?php echo $page_name; ?>">
        <header class="top">
                <?php include BASE_PATH.'inc/nav.php'; ?>
            </header>
        <div class="body">
            <section class="main-container">
               <?php get_header('Brafton Plugin and Module Version Control'); ?>
                <div class="description">
                <h2>Plugin &amp; Module Information</h2> 
                    <p>Below is a list of all the Brafton, ContentLEAD, and Castleford Plugins &amp; Modules available for Automation of content Delivery.  Expanding and individual Item will display a complete list of the last 10 versions available for that plugin/module with a link to download it from our archive repository.
                    </p>
                </div>
                <section class="version-container">
                    <span id="go-left" class="left-nav"><i class="fa fa-caret-left"></i></span>
                    <span id="go-right" class="right-nav"><i class="fa fa-caret-right"></i></span>
                    <div id="overflow-container">
                    <?php for($i=0;$i<count($system);$i++){ ?>
                    <article class="master-display thirds <?php echo $system[$i]['name']; ?>-table" style="background-image: url('/images/<?php echo $system[$i]['logo']; ?>');">
                        <div class="info-container">
                            <h2><?php echo $system[$i]['name']; ?> <i id="<?php echo $system[$i]['name']; ?>"class="fa fa-external-link expand"></i></h2>
                            <?php 
                            $version = $con->retrieveData(strtolower($system[$i]['name']), '*', array("ORDER BY" => array("Vid DESC LIMIT 1"))); ?>
                            <div>
                                <!--<span>Support Ended On: <?php if($system[$i]['support_end']){ echo $system[$i]['support_end'];} else{ echo 'N/A'; } ?></span>-->
                            </div>
                            <div class="information">
                                <span><b>Supported Since:</b><?php echo $system[$i]['supported_since']; ?></span>
                                <?php for($o=0;$o<count($version);$o++){ ?>
                                <span><?php echo '<b>Version:</b> '.$version[0]['version']. ' ' .$version[0]['code_name']; ?></span>
                                <span><?php echo '<b>Requires:</b> '.$system[$i]['name'] .' : '.$version[0]['requires']; ?></span>
                            </div>
                            <div class="download_link">
                                <span class="download"><a href="<?php echo $version[0]['download_link']; ?>" title="Download <?php echo $system[$i]['name']; ?> Importer"><i class="fa fa-download fa-4x"></i></a> </span>
                            </div>
                                <?php } ?>
                        </div>
                    </article>
                    <?php } ?>
                    </div>
                </section>
            </section>
            <div class="importer-details-container popUp-info">
                <?php if(!isset($_COOKIE['view_versions_help_1'])){ ?>
                    <div class="instructions view_versions_help_1">
                        Click anywhere outside the list to return to the previous screen
                    </div>
                <?php } ?>
                <div class="importer-details"></div>
            </div>
            <!--<footer>
                Relavant Docs:<br>
                Naming Conventions <a href="https://docs.google.com/a/brafton.com/document/d/1JHfReBDf1dxxS83tgDUtjfIM9T3fR__jSwPtWqXrUrg/edit?usp=sharing">Doc</a><br>
                Numbering Conventions <a href="https://docs.google.com/a/brafton.com/document/d/19fLW1PrUsuvZq49m1fPHV7gX_Tkvftk3NK57QTaDBL0/edit?usp=sharing">Doc</a>
        </footer>-->
        </div>
    </body>
</html>