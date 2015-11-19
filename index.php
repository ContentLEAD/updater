<?php
require_once 'load.php';
$system = $con->retrieveData('systems','*');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control</title>
        <link rel="stylesheet" href="/css/style.css">
        <!--Jquery from google cdn-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="/js/brafton.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="body">
            <head>
                <?php get_header('Brafton Plugin and Module Version Control'); ?>
            </head>
            <section>
            <h2>Plugin &amp; Module Information</h2> 
                <p>Below is a list of all the Brafton, ContentLEAD, and Castleford Plugins &amp; Modules available for Automation of content Delivery.  Expanding and individual Item will display a complete list of the last 10 versions available for that plugin/module with a link to download it from our archive repository.
                </p>
                <?php for($i=0;$i<count($system);$i++){ ?>
                <h2><?php echo $system[$i]['name']; ?> <img src="images/expand.png" id="<?php echo $system[$i]['name']; ?>" class="expand" title="List previous versions"></h2>
                <table class="master-display full <?php echo $system[$i]['name']; ?>-table">
                    <?php 
                    $version = $con->retrieveData(strtolower($system[$i]['name']), '*', array("ORDER BY" => array("Vid DESC LIMIT 1"))); ?>
                    <tr>
                        <td>Supported Since: <?php echo $system[$i]['supported_since']; ?></td>
                        <td>Support Ended On: <?php if($system[$i]['support_end']){ echo $system[$i]['support_end'];} else{ echo 'N/A'; } ?></td>
                    </tr>
                    <tr>
                        <?php for($o=0;$o<count($version);$o++){ ?>
                        <td><?php echo 'Current Version: '.$version[0]['version']; ?></td>
                        <td><?php echo 'Version Name: '.$version[0]['code_name']; ?></td>
                        <td><?php echo $version[0]['description']; ?></td>
                        <td><?php echo 'Requires Version: '.$version[0]['requires']; ?></td>
                    </tr>
                    <tr>
                        <td class="download"><a href="<?php echo $version[0]['download_link']; ?>">Download Importer</a> </td>
                    </tr>
                        <?php } ?>
                    </tr>
                </table>
                <?php } ?>
            </section>
            <div class="importer-details"></div>
            <div class="importer-details-close">X</div>
            <footer>
                Relavant Docs:<br>
                Naming Conventions <a href="https://docs.google.com/a/brafton.com/document/d/1JHfReBDf1dxxS83tgDUtjfIM9T3fR__jSwPtWqXrUrg/edit?usp=sharing">Doc</a><br>
                Numbering Conventions <a href="https://docs.google.com/a/brafton.com/document/d/19fLW1PrUsuvZq49m1fPHV7gX_Tkvftk3NK57QTaDBL0/edit?usp=sharing">Doc</a>
        </footer>
        </div>
    </body>
</html>