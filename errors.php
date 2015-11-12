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
if($page = isset($_GET['page'])? $_GET['page'] : false){
    $start = $page == 1? 0 : ($page -1) * 25;
}else{
    $start = 0;
    $page = 1;
}
$sort = '';
if(isset($_GET['sort'])){
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
for($i=0;$i<count($results);$i++){
    $results[$i]['errors'] = $con->retrieveData('errors', '*', array(
        'WHERE' => array(
            sprintf("domain = '%s' ORDER BY date DESC", $results[$i]['domain']))));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Brafton Plugin/Module Version Control STAGING</title>
        <?php include_once 'inc/scripts.php'; ?>
    </head>
    <body>
        <div class="body">
            <head>
                <?php get_header('Error Report'); ?>
            </head>
            <section class="main-container">
                <div class="error_holder">
                <div id="sorting">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="sorting-form" style="font-size:22px;">
                    Display only: <?php create_cms_drop('sort', 'Select CMS'); ?>
                    </form>
                </div>
                <div id="delete-notice" style="display:none; color:red;float:left; max-width:50%;">
                    <button id="submit-error-deletion">Delete Selection</button>
                </div>
                <nav class="pagination">
                    <?php if($page >= 2){ ?><a href="/errors.php?page=<?php echo $page -1 . $sort; ?>"><<< PREVIOUS </a> | <?php } ?><a href="/errors.php?page=<?php echo ++$page. $sort; ?>">NEXT >>></a><br/>Total of <?php echo $total_count; ?> Errors
                </nav>
                    <input type="button" id="mass_delete" value="mass_delete">
                    <div class="clear-fix"></div>
                <?php 
                foreach($results as $result){ ?>
                    <div class="error_card" id="<?php echo $result['domain']; ?>">
                        <span class="domain-select"><input type="checkbox" name="domain-select[]" value="<?php echo $result['domain']; ?>"><span>DELETE</span></span>
                        <div class="error_cont">
                            <h2><?php echo $result['domain']; ?></h2>
                            <h3><?php echo $result['errors'][0]['type']; ?></h3>
                            <p>Total Errors: <?php echo count($result['errors']); ?></p>
                            <div class="error_list">
                                <?php if(!isset($_COOKIE['error_help_1'])){ ?>
                                    <div class="instructions error_help_1">
                                        Click anywhere outside the list to return to the previous screen
                                    </div>
                                <?php } ?>
                                <div class="error_listing">
                                    <a href="//<?php echo $result['domain']; ?>"><?php echo $result['domain']; ?></a>
                                <?php foreach($result['errors'] as $errors){ ?>
                                    <section class="newError">
                                        <?php $data = json_decode($errors['error']); ?>
                                        <div class="error-report" id="error-<?php echo $errors['Eid']; ?>">
                                            <div>
                                               <div class="delete-error" id="<?php echo $errors['Eid']; ?>"><input type="checkbox" data-error="<?php echo $errors['Eid']; ?>" class="delete-individual-error">Delete</div><h3>Error for <?php echo $errors['type']; ?> on <?php echo $errors['date']; ?></h3>Salesforce Status: <?php if($errors['sync']){ echo 'Ticket Created'; }else{ echo 'No Ticket Created'; } ?>
                                            </div>
                                            <div class="report">
                                               <?php 
                                                $vars = get_object_vars($data); 
                                                foreach($vars as $key => $value){
                                                    if($key == 'error'){ 
                                                        echo '</span><span>';
                                                        echo '<span>'.$value.'</span>';
                                                    }else{
                                                        echo '<span>'.$key.' is: '.$value.'</span>';
                                                    }
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </section>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                    </div>
            </section>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="delete-items">
                    <input type="hidden" name="deleting_items" value="submition">
                </form>
                <form action="" method="post" id="domain-delete">
                    <input type="hidden" name="deleting_domain" value="submition">
                </form>
        </div>
    </body>
</html>