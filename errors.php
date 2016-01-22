<?php 
require_once 'load.php';
if(isset($_POST['deleting_items']) && isset($_POST['error-id'])){
    $error_ids = implode(',',$_POST['error-id']);
    $delete = 'DELETE FROM errors WHERE Eid in ('.$error_ids.')';
    $result_delete = $con->customQuery($delete);
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
$total_errors = count($total);
$results = $con->retrieveData('errors','domain', array(
                                                'WHERE' => array(
          " $cond GROUP BY domain LIMIT $start, 25")));
$total_clients = 0;
for($i=0;$i<count($results);$i++){
    $results[$i]['errors'] = $con->retrieveData('errors', '*', array(
        'WHERE' => array(
            sprintf("domain = '%s' ORDER BY date DESC", $results[$i]['domain']))));
    $prots = array(
            '/http:\/\//',
            '/https:\/\//',
            '/www./',
            '/.com/'
        );
    $results[$i]['client'] = preg_replace($prots,'',$results[$i]['domain']);
    $total_clients++;
}
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
                <?php get_header('Error Report'); ?>
                <div class="error_holder">
                <div id="sorting">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" id="sorting-form" style="font-size:22px;">
                    <?php create_cms_drop('sort', 'Filter by CMS'); ?>
                    </form>
                </div>
                <div id="delete-notice">
                    <button id="submit-error-deletion">Delete Selection</button>
                </div>
                    <span class="results-details">Total of <span id="total_errors"><?php echo $total_errors; ?></span> Errors among <span id="total_clients"><?php echo $total_clients; ?></span> Clients</span>
                <?php if($total_clients > 12){ ?>
                    <nav class="pagination">
                        <?php if($page >= 2){ ?><a href="/errors.php?page=<?php echo $page -1 . $sort; ?>"><<< PREVIOUS </a> | <?php } ?><a href="/errors.php?page=<?php echo ($page + 1). $sort; ?>">NEXT >>></a>
                    </nav>
                <?php } ?>
                    <input type="button" id="mass_delete" value="mass_delete">
                    <div class="clear-fix"></div>
                <?php 
                foreach($results as $result){ ?>
                    <div class="error_card" id="<?php echo $result['domain']; ?>">
                        <span class="domain-select">
                            <label class="switch">
                                <input class="switch-input" type="checkbox" name="domain-select[]" value="<?php echo $result['domain']; ?>"><!--<span>DELETE</span>--><span class="switch-label" data-on="Delete" data-off="Keep"></span><span class="switch-handle"></span>
                            </label>
                        </span>
                        <div class="error_cont">
                            <h2><?php echo $result['client']; ?></h2>
                            <h3><?php echo $result['errors'][0]['type']; ?></h3>
                            <p>Total Errors: <span class="this-domains-errors"><?php echo count($result['errors']); ?></span></p>
                            <span class="domain_link"><a href="http://<?php echo $result['domain']; ?>" target="_blank"><?php echo $result['domain']; ?></a></span>
                            <div class="error_list">
                                <?php if(!isset($_COOKIE['error_help_1'])){ ?>
                                    <div class="instructions error_help_1">
                                        Click anywhere outside the list to return to the previous screen
                                    </div>
                                <?php } ?>
                                <div class="error_listing">
                                    <a href="//<?php echo $result['domain']; ?>" target="_blank" onclick="stopPropagation()"><?php echo $result['domain']; ?></a>
                                <?php foreach($result['errors'] as $errors){ ?>
                                    <section class="newError">
                                        <?php $data = json_decode($errors['error']); ?>
                                        <div class="error-report" id="error-<?php echo $errors['Eid']; ?>">
                                            <div>
                                               <div class="delete-error" id="<?php echo $errors['Eid']; ?>">
                                                   <label class="switch">
                                                       <input type="checkbox" data-error="<?php echo $errors['Eid']; ?>" class="switch-input delete-individual-error"><span class="switch-label" data-on="Delete" data-off="Keep"></span><span class="switch-handle"></span>
                                                   </label>
                                                </div>
                                                <h3>Error for <?php echo $errors['type']; ?> reported at <?php echo $errors['date']; ?></h3>Salesforce Status: <?php if($errors['sync']){ echo 'Ticket Created'; }else{ echo 'No Ticket Created'; } ?>
                                            </div>
                                            <div class="report">
                                               <?php 
                                                $vars = get_object_vars($data);                             
                                                $apiKey = '';
                                                $brand = '';
                                                foreach($vars as $key => $value){
                                                    if($key == 'error'){ 
//                                                        echo '</span><span>';
                                                        echo '<span class="reported_error">'.$value.'</span>';
                                                    }else{
                                                        if($key == 'Domain'){
                                                            continue;
                                                        }
                                                        $apiKey = $key == 'API'? $value : $apiKey;
                                                        $brand = $key == 'Brand'? $value : $brand;
                                                        echo '<span><b>'.$key.'</b>: '.$value.'</span>';
                                                    }
                                                }
                                                                            echo '<a href="http://'.$brand.'/'.$apiKey.'">FEED</a>';
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
                <?php if($total_clients > 12){ ?>
                <nav class="pagination">
                    <?php if($page >= 2){ ?><a href="/errors.php?page=<?php echo $page -1 . $sort; ?>"><<< PREVIOUS </a> | <?php } ?><a href="/errors.php?page=<?php echo ($page + 1). $sort; ?>">NEXT >>></a>
                </nav>
                    <?php } ?>
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