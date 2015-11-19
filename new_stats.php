<?php 
require_once 'load.php';
$results = $con->retrieveData('log_versions', 'COUNT(lid)', array(
                                                                'WHERE' => array(
                                                                    " importer = 'wordpress' ")));
$wp_users = $results[0]['COUNT(lid)'] - 2;
$all_results = $con->retrieveData('log_versions', '*');
$out = array();
foreach ($all_results as $key => $value){
    foreach ($value as $key2 => $value2){
        if($key2 == 'importer' || $key2 == 'brand' || $key2 == 'version'){
            $index = $key2.'-'.$value2;
            if (array_key_exists($index, $out)){
                $out[$index]++;
            } else {
                $out[$index] = 1;
            }
        }
    }
}
$output = $out;
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
                <?php get_header('Current User Stats'); ?>
                
                <div class="quick">
                    <h2>Quick Stats</h2>
                    <label>Total Wordpress Users</label><span class="result"><?php echo $output['importer-wordpress']; ?></span><label>Total Drupal 7 Users</label><span class="result"><?php echo $output['importer-drupal7']; ?></span><label>Total Joomla 3 Users</label><span class="result"><?php echo $output['importer-joomla3']; ?></span><label>Total Hubspot Users</label><span class="result"><?php echo $output['importer-hubspot-cos']; ?></span><label>Total DNN Users</label><span class="result"><?php echo $output['importer-dnn']; ?></span><br/>
                     <span class="disclaimer" style="display:block;">Stats reflected are based on activated plugins per unique domain name.  Stats do not reflect ACTIVE clients.</span>
                </div>
                <div class="quick_brand">
                    <h2>Stats by Brand</h2>
                    <label>Brafton Users</label><span class="results"><?php echo $output['brand-api.brafton.com']; ?></span><label>ContentLEAD Users</label><span class="results"><?php echo $output['brand-api.contentlead.com']; ?></span><label>Castleford Users</label><span class="results"><?php echo $output['brand-api.castleford.com.au']; ?></span>
                    <span class="disclaimer" style="display:block;">Only applies if client as upgraded at least once.  Stats here may be misleading.</span>
                </div>
            </section>
                
        </div>
    </body>
</html>