<?php
require_once 'load.php';
$table = $_GET['plugin'];
$cond = array(
        'ORDER BY'  => array(
            "Vid DESC LIMIT 0, 10"));
if(isset($_GET['paged'])){
    $page = $_GET['paged'];
    $page = $page * 10;
    //build paging stament here
    $cond = array(
        'ORDER BY'  => array(
            "Vid ASC LIMIT $page, 10"));
}
$results = $con->retrieveData($table, '*', $cond);
?>
<section class="details full">
    <h2><?php echo strtoupper($table); ?></h2>
    <?php if(!$results){ echo '<h2>No previous verions to display</h2>'; }
    else{
        for($i=0;$i<count($results);++$i){ ?>
    <article class="version-details">
        <!--<h3><?php echo $results[$i]['name']; ?><!--<span class="options"><a href="<?php echo $table.'/repository/'.$table.'-'.$results[$i]['version'].'.zip'; ?>"><img src="images/download.png" class="down-icon"></a></span></h3>-->
        <p><?php echo $results[$i]['code_name'].' ver: '.$results[$i]['version']; ?></p>
            <a href="<?php echo $table.'/repository/'.$table.'-'.$results[$i]['version'].'.zip'; ?>" class="download-stored-repo" title="Download this version of the <?php echo $results[$i]['name']; ?> "><i class="fa fa-download fa-4x"></i></a>
            <ul>
            <?php 
            $feats =  $results[$i]['features'];
            $array = array();
            $array = unserialize($feats);
            foreach($array as $feature){
                echo '<li>'.$feature.'</li>';
            } ?>
            </ul>
        <p>
        <?php echo $results[$i]['description']; ?>
        </p>
    </article>
        <?php }
    }
    if(isset($page)){ ?>
        <!-- pagination here --> 
    <?php } ?>
</sesction>