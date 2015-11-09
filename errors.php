<?php 
require 'classes/connect.php';
require 'inc/utils.php';
$con = new DBConnect();
if(isset($_POST['deleting_items'])){
    $error_ids = implode(',',$_POST['error-id']);
    $delete = 'DELETE FROM errors WHERE Eid in ('.$error_ids.')';
    $result_delete = $con->customQuery($delete);
    echo $result_delete;
}
$cond = "sync = '0'";
if($page = $_GET['page']){
    $start = $page == 1? 0 : ($page -1) * 25;
}else{
    $start = 0;
    $page = 1;
}
$sort = '';
if($_GET['sort']){
    $type = $_GET['sort'];
    $cond .= " AND type = '$type'";
    $sort = '&sort='.$type;
}
$total = $con->retrieveData('errors','*', array(
                                                'WHERE' => array(
                                                    " $cond ORDER BY date DESC")));
$total_count = count($total);
$results = $con->retrieveData('errors','domain', array(
                                                'WHERE' => array(
          " $cond GROUP BY domain LIMIT $start, 25")));
//$custom = "SELECT e.Domain, (SELECT ie.error FROM errors AS ie WHERE ie.Domain = e.Domain) AS Errors FROM errors AS e GROUP BY e.Domain";
//$results = $con->customQuery($custom);
for($i=0;$i<count($results);$i++){
    $results[$i]['errors'] = $con->retrieveData('errors', '*', array(
        'WHERE' => array(
            sprintf("domain = '%s' ORDER BY date DESC", $results[$i]['domain']))));
}
?>
<!DOCTYPE html>
<?php echo '<pre>';
var_dump($results);
echo '</pre>';
?>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control STAGING</title>
        <link rel="stylesheet" href="/css/style.css">
        <!--Jquery from google cdn-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="/js/brafton.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="body">
            <head>
                <?php get_header('Error Report'); ?>
            </head>
            <section class="">
                <div id="delete-notice" style="display:none; color:red;float:left; max-width:50%;">
                    <button id="submit-error-deletion">Delete Selection</button>
                </div>
                <div id="sorting">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="sorting-form" style="font-size:22px;">
                    Display only: <?php create_cms_drop('sort', 'Select CMS'); ?>
                    </form>
                </div>
                <nav class="pagination">
                    <?php if($page >= 2){ ?><a href="/errors.php?page=<?php echo $page -1 . $sort; ?>"><<< PREVIOUS </a> | <?php } ?><a href="/errors.php?page=<?php echo ++$page. $sort; ?>">NEXT >>></a><br/>Total of <?php echo $total_count; ?> Errors
                </nav>
                <?php 
                foreach($results as $result){ ?>
                   <div class="error_card">
                    <h2><?php echo $result['domain']; ?></h2>
                       <h3><?php echo $result['errors'][0]['type']; ?></h3>
                       <p>Total Errors: <?php echo count($result['errors']); ?></p>
                       <div class="error_list">
                           <?php foreach($result['errors'] as $errors){ ?>
                            
                           <?php } ?>
                       </div>
                    </div>
                <?php } ?>
            </section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="delete-items">
                    <input type="hidden" name="deleting_items" value="submition">
                </form>
        </div>
    </body>
</html>