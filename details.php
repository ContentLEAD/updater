<?php include 'classes/connect.php'; ?>
<?php 
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

$details = new DBConnect();
$results = $details->retrieveData($table, '*', $cond);
?>
<h2><?php echo strtoupper($table); ?></h2>
<span>Previous 10 updates</span>
<table class="details full">
    <tr>
        <th>Plugin Name</th>
        <th>Version</th>
        <th>Details</th>
        <th>Features/Bugs</th>
    </tr>
    <?php if(!$results){ echo '<h2>No previous verions to display</h2>'; }
    else{
        for($i=0;$i<count($results);++$i){ ?>
    <tr>
        <td style="position:relative;"><?php echo $results[$i]['name']; ?><span class="options"><a href="<?php echo $table.'/repository/'.$table.'-'.$results[$i]['version'].'.zip'; ?>"><img src="images/download.png" class="down-icon"></a></span></td>
        <td><?php echo $results[$i]['code_name'].' ver: '.$results[$i]['version'];; ?></td>
        <td><?php echo $results[$i]['description']; ?></td>
        <td><ul>
            <?php 
            $feats =  $results[$i]['features'];
            $array = array();
            $array = unserialize($feats);
            foreach($array as $feature){
                echo '<li>'.$feature.'</li>';
            } ?>
            </ul>
        </td>
    </tr>
        <?php }
    }
    if(isset($page)){ ?>
        <!-- pagination here --> 
    <?php } ?>
</table>