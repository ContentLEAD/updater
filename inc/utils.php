<?php
date_default_timezone_set('America/New_York');
$date = date('Y-m-d H:i:s');
//displays a list of importer for selection from the systems table
function importer_list($plugin=NULL){
    $con = new DBConnect();
    $results = $con->retrieveData('systems', 'name');
    ?><select name="system" class="system">
        <option>Select an Importer</option><?php 
    foreach($results as $field => $val){ ?>
        <option <?php if($plugin == $val['name']){ echo 'selected'; }?>> <?php echo $val['name']; ?></option>

    <?php } ?>
    </select><?php 
}

function create_cms_drop($name = 'system', $default = null){
    $con = new DBConnect();
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
    $con = new DBConnect();
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
    $custom = array();
    $support_start = $_POST['supported_since'];
    $data = array(
        'name' => $sys,
        'supported_since' => $support_start
    );
    $con = new DBConnect();
    //insert importer name and date created into the systems table
    if(!$con->insertData('systems', $data)){
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
    //prepare create table statement for the db, table will hold all our importer details
    $sql = "CREATE TABLE `$sys` (
  `Vid` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(10) DEFAULT NULL,
  `description` longtext,
  `name` varchar(75) DEFAULT NULL,
  `requires` varchar(45) DEFAULT NULL,
  `tested` varchar(45) DEFAULT NULL,
  `last_updated` varchar(45) DEFAULT NULL,
  `download_link` longtext DEFAULT NULL,
  `code_name` varchar(75) DEFAULT NULL,
  `features` longtext,
  `custom` longtext,
  PRIMARY KEY (`Vid`),
  UNIQUE KEY `version_UNIQUE` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";
    $con = new DBConnect();
    if(!$con->idle_query($sql)){
        echo 'error'; 
        return;
    }
    
    //Insert our importer details into the newly created table
    $pname = $_POST['IName'];
    $ver = $_POST['version'];
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
    $ncon = new DBConnect();
    if(!$ncon->insertData($sys, $data)){
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
    

}
//creates importer dir and archive dir while adding the premade error page for auto error loging and update for auto update features
function new_importer_files($plugin){
    try{
        mkdir($plugin);
        mkdir($plugin.'/repository');
        copy('templates/error.php', $plugin.'/error.php');
        copy('templates/update.php', $plugin.'/update.php');
        return true;
    }catch(Exception $e){
        return false;   
    }
}
//downloads copy of the importer from where ever we will be keeping it.
function download_importer($plugin,$file_name,$download_link) {
   try{
        $repo_name = $plugin.'/repository/'.$file_name;
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
    $con = new DBConnect();
    if(!$con->insertData($sys, $data)){
        echo 'ERROR';
        return;
    }
    //download copy of the importer to the archive
    if(!download_importer($sys, $sys.'-'.$ver.'.zip', $download)){
        echo 'Error downloading copy of file';
        return;
    }
    header('LOCATION:settings.php');
    
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
    $con = new DBConnect();
    if(!$con->updateData($sys, $data, array('WHERE' => array("version='$ver'")))){
        echo 'ERROR';
        return;
    }
    header('LOCATION:settings.php');
    
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
function get_header($title){
    global $con;
    $page_title = $title;
    include 'header.php';
}
?>