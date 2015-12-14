<?php
session_start();
date_default_timezone_set('America/New_York');
$date = date('Y-m-d');
$dateTime = date('Y-m-d h:m:s');
if(file_exists(BASE_PATH.'classes/database/creds.php')){
    require_once BASE_PATH.'classes/database/database.php';
}
if(file_exists(BASE_PATH . 'inc/setupFunctions.php') && $con->get_env() != 'LOCAL DEV'){
    define("HUB_BASE", "/var/www/html/tech/hubspot/cos/");
    include_once BASE_PATH . 'inc/setupFunctions.php';
}else{
    define("HUB_BASE", BASE_PATH."/logs/");
    $devMessage = "You are running this application in a local development enviorment.  the required files and directory structure do not exist therefore the hubspot features will not work correctly";
    include_once BASE_PATH . 'inc/setupFunctions.php';
}
//displays a list of importer for selection from the systems table
function importer_list($plugin=NULL){
    global $con;
    $results = $con->retrieveData('systems', 'name');
    ?><select name="system" class="system">
        <option>Select an Importer</option><?php 
    foreach($results as $field => $val){ ?>
        <option <?php if($plugin == $val['name']){ echo 'selected'; }?>> <?php echo $val['name']; ?></option>

    <?php } ?>
    </select><?php 
}

function create_cms_drop($name = 'system', $default = null){
    global $con;
    $results = $con->retrieveData('systems', 'name');
    ?><select name="<?php echo $name; ?>">
<?php if($default != null){ ?>
<option value="<?php echo $default; ?>"><?php echo $default; ?></option>
<?php } ?>
<?php 
    foreach($results as $field => $val){ ?>
        <option><?php echo $val['name']; ?></option>

    <?php } ?>
    </select><?php 
}
//displays list of codenames available for use from the codes table
function display_code_names($name = NULL){
    global $con;
    $results = $con->retrieveData('codes', 'name', array('WHERE' => array('active=1')));
    ?><select name="codes"><option>Select an Importer</option><?php 
    foreach($results as $field => $val){ ?>
        <option <?php if($name == $val['name']){ echo 'selected'; } ?>><?php echo $val['name']; ?></option>

    <?php } ?>
    </select><?php   
}
//displays list pf field types for db table creation **May not be used
function field_type(){
    $types = array('int', 'varchar', 'date', 'longtext', 'mediumtext');
        ?><select name="codes"><?php 
    foreach($results as $field => $val){ ?>
        <option><?php echo $val['name']; ?></option>

    <?php } ?>
    </select><?php  
}
//function for creating new importer
function new_importer(){
    $sys = $_POST['system'];
    $sys = str_replace(' ','',$sys);
    $sys = ucfirst(strtolower($sys));
    $custom = array();
    $support_start = $_POST['supported_since'];
    
    $file = $_FILES['systemLogo']['tmp_name'];
    $name = $_FILES['systemLogo']['name'];
    $moveTo = BASE_PATH . 'images/'.$name;
    move_uploaded_file($file, $moveTo);
    $data = array(
        'supported_since' => $support_start,
        'logo'  => $name
    );
    global $con;
    //insert importer name and date created into the systems table
    if(!$con->updateData('systems', $data,array('WHERE' => array("name='$sys'")) )){
        echo 'Error: Could not add Importer to the database.  Please see the DB Administrator.';
        return;
    }
    $fields = NULL;
    //if we need custom fields for an auto update functionality we can add those here
    if(isset($_POST['fieldName'])){
        $fields = array();
        for($i=0;$i<count($_POST['fieldName']);++$i){
            $fields[] = array(
                'name'      => $_POST['fieldName'][$i],
                'value'     => $_POST['fieldValue'][$i]
            );
        }
        $fields = serialize($fields);
    }
    $sys = strtolower($sys);
    //require_once BASE_PATH . 'classes/database/tables/plugin_creation.php';
    //prepare create table statement for the db, table will hold all our importer details
    
    //Insert our importer details into the newly created table
    $pname = $_POST['IName'];
    $ver = implode('.',$_POST['version']);
    $desc = $_POST['description'];
    $requires = $_POST['requires'];
    $tested = $_POST['tested'];
    $download = $_POST['download'];
    $cname = $_POST['codes'];
    $features = $_POST['features'];
    $features = explode(',',$features);
    $features = serialize($features);
    $data = array (
        'version'       => $ver,
        'description'   => $desc,
        'name'          => $pname,
        'requires'      => $requires,
        'tested'        => $tested,
        'last_updated'  => $support_start,
        'download_link' => $download,
        'code_name'     => $cname,
        'features'      => $features,
        'custom'        => $fields,
    );
    if(!$con->insertData($sys, $data)){
        echo 'ERROR';
        return;
    }
    //copy needed importer directory and files for auto update features, error reporting, archive copies of the importer
    if(!new_importer_files($sys)){
        echo 'Error copying need files to the new importers directory';
        return;
    };
    //download copy of the importer to the archive
    if(!download_importer($sys, $sys.'-'.$ver.'.zip', $download)){
        echo 'Error downloading copy of file';
        return;
    }
    $_SESSION['previousAction'] = "Added a new Plugin or Module $pname ";
    if(isset($_SESSION['previousAction'])){
        header("LOCATION:settings");   
    }

}
//creates importer dir and archive dir while adding the premade error page for auto error loging and update for auto update features
function new_importer_files($plugin){
    try{
        mkdir(BASE_PATH.$plugin);
        chmod(BASE_PATH.$plugin, 775);
        mkdir(BASE_PATH.$plugin.'/repository');
        chmod(BASE_PATH.$plugin.'/repository', 775);
        copy(BASE_PATH.'templates/error.php', BASE_PATH.$plugin.'/error.php');
        copy(BASE_PATH.'templates/update.php', BASE_PATH.$plugin.'/update.php');
        return true;
    }catch(Exception $e){
        return false;   
    }
}
//downloads copy of the importer from where ever we will be keeping it.
function download_importer($plugin,$file_name,$download_link) {
   try{
        $repo_name = BASE_PATH.$plugin.'/repository/'.$file_name;
        $src = fopen($download_link,'r');
        $dest = fopen($repo_name,'w');
        stream_copy_to_stream($src, $dest);
       return true;
   }catch(Exception $e){
       return false;
   }
}
function update_importer(){
    $sys = $_POST['system'];
    $sys = str_replace(' ','',$sys);
    $sys = strtolower($sys);
    $support_start = $_POST['supported_since'];
    $pname = $_POST['IName'];
    $ver = implode('.',$_POST['version']);
    $desc = $_POST['description'];
    $requires = $_POST['requires'];
    $tested = $_POST['tested'];
    $download = $_POST['download'];
    $cname = $_POST['codes'];
    $features = $_POST['features'];
    $features = explode(',',$features);
    $features = serialize($features);
    $fields = NULL;
    //if we need custom fields for an auto update functionality we can add those here
    if(isset($_POST['fieldName'])){
        $fields = array();
        for($i=0;$i<count($_POST['fieldName']);++$i){
            $fields[] = array(
                'name'      => $_POST['fieldName'][$i],
                'value'     => $_POST['fieldValue'][$i]
            );
        }
        $fields = serialize($fields);
    }
    $data = array (
        'version'       => $ver,
        'description'   => $desc,
        'name'          => $pname,
        'requires'      => $requires,
        'tested'        => $tested,
        'last_updated'  => $support_start,
        'download_link' => $download,
        'code_name'     => $cname,
        'features'      => $features,
        'custom'        => $fields,
    );
    global $con;
    if(!$con->insertData($sys, $data)){
        echo 'ERROR';
        return;
    }
    //download copy of the importer to the archive
    if(!download_importer($sys, $sys.'-'.$ver.'.zip', $download)){
        echo 'Error downloading copy of file';
        return;
    }else{
        $_SESSION['previousAction'] = "Updated the $pname ";
    }
    if(isset($_SESSION['previousAction'])){
        header('LOCATION:settings');
    }else{
        header('LOCATION:/');   
    }
    
}
function edit_importer(){
    $sys = $_POST['system'];
    $sys = str_replace(' ','',$sys);
    $sys = strtolower($sys);
    $support_start = $_POST['supported_since'];
    $pname = $_POST['IName'];
    $ver = implode('.',$_POST['version']);
    $desc = $_POST['description'];
    $requires = $_POST['requires'];
    $tested = $_POST['tested'];
    $download = $_POST['download'];
    $cname = $_POST['codes'];
    $features = $_POST['features'];
    $features = explode(',',$features);
    $features = serialize($features);
    $fields = NULL;
    //if we need custom fields for an auto update functionality we can add those here
    if(isset($_POST['fieldName'])){
        $fields = array();
        for($i=0;$i<count($_POST['fieldName']);++$i){
            $fields[] = array(
                'name'      => $_POST['fieldName'][$i],
                'value'     => $_POST['fieldValue'][$i]
            );
        }
        $fields = serialize($fields);
    }
    $data = array (
        'version'       => $ver,
        'description'   => $desc,
        'name'          => $pname,
        'requires'      => $requires,
        'tested'        => $tested,
        'last_updated'  => $support_start,
        'download_link' => $download,
        'code_name'     => $cname,
        'features'      => $features,
        'custom'        => $fields,
    );
    global $con;
    if(!$con->updateData($sys, $data, array('WHERE' => array("version='$ver'")))){
        echo 'ERROR';
        return;
    }
    $_SESSION['previousActin'] = "Edited the $pname ";
    header('LOCATION:settings');
    
}
function gen_key() {
    $alphabet = "abcdefghijklmnopqrstuwxyz";
    $alphabet .= '123456789';
    $alphabet .= $alphabet.$alphabet.$alphabet;
    $alphabet = str_split($alphabet);
    for ($i = 0; $i < 24; $i++) {
        $n = rand(0, count($alphabet)-1);
        $pass[$i] = $alphabet[$n];
    }
    return implode('',$pass);
}
function get_header($page_title, $sub_title = ''){
    global $con;
    include 'header.php';
}
function page_name(){
    $raw_url = $_SERVER['REQUEST_URI'];
    $page_pos = strrpos($raw_url,'/');
    $page = substr($raw_url, ($page_pos + 1));
    if($query_pos = strrpos($page,'?')){
        $page = explode('?', $page);
        $page = $page[0];
    }
    $page = explode('.',$page);
    return $page[0];
}

//Set Master Variables
$page_name = page_name();
?>