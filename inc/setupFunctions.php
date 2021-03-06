<?php
if(file_exists(HUB_BASE.'Hubspot-COS/BraftonLibrary/BraftonLibrary.php')){
    include_once HUB_BASE.'Hubspot-COS/BraftonLibrary/BraftonLibrary.php';
}
function matchOptions($value, $match, $term){
    if($value == $match){
        echo $term;
    }
}
function blog_list($params){
    $url = 'https://api.hubapi.com/content/api/v2/blogs';

    $url_params=params_to_string($params);

    $blogsInfo = execute_get_request($url . $url_params);

    $blogArray = array();
    $blogString;
    foreach ($blogsInfo->objects as $blog){
        $blogArray[] = array(
            'BlogName'  => $blog->html_title,
            'BlogId'    => $blog->id
            );
    }
    foreach($blogArray as $key){
        $name = $key['BlogName'];
        $id = $key['BlogId'];
        $blogString .= "<option value='$id'>{$name}($id)";
        $blogString .= "</option>";
    }
    return $blogString;
}
function author_list($key){
    $url = 'https://api.hubapi.com/blogs/v3/blog-authors?hapikey='. $key .'&casing=snake_case';

    $authorsInfo = execute_get_request($url);
    $authorArray = array();
    $blogString;
    foreach ($authorsInfo->objects as $author){
        $authorArray[] = array(
            'AuthorName'    => $author->fullName,
            'AuthorId'      => $author->id
            );
    }
    foreach($authorArray as $key){
        $name = $key['AuthorName'];
        $id = $key['AuthorId'];
        $blogString .= "<option value='$id'>{$name}($id)</option>";
    }
    return $blogString;
    
}
function folder_list($key){
    $url = 'http://api.hubapi.com/filemanager/api/v2/folders?hapikey=' . $key;
    
    $folderInfo = execute_get_request($url);
    $folderArray = array();
    $blogString;
    foreach ($folderInfo->objects as $folder){
        $folderArray[] = array(
            'folderName'    => $folder->name,
            'folderId'      => $folder->id,
            'folderPath'    => $folder->full_path
            );
    }
    $blogString .= "<option value='brafton_images'>Brafton_images</option>";
    foreach($folderArray as $key){
        $name = $key['folderName'];
        $path = $key['folderPath'];
        $blogString .= "<option value='$path'>{$name}</option>";
    }
    return $blogString;
    
}
function add_client(){
    $txtString = array();
    foreach($_POST as $key => $val){
        $$key = $val;
        $txtString[$key] = $val;
    }
    $txtString = json_encode($txtString);
    if(!is_dir(HUB_BASE.$client)){
        $newDir = mkdir(HUB_BASE.$client, 0777);        
    }
    $string = "<?php";
$string .=<<<EOC
   define("domain", "$domain"); //api domain
    define("client", "$client");
define("brafton_apiKey", "$brafton_apiKey"); //api key
define("hub_apiKey","$hub_apiKey"); //hubspit key
define("blog_id","$blog_id"); //blog id
define('post_status', "$post_status");
define("dynamic_author", $dynamic_author);
define("author_id","$author_id"); //author of articles
define("portal","$portal"); //hub portal id
define("image_import", $image_import); // change to true to upload images to clients hubspot account 
define("image_folder", "$image_folder"); //folder to upload images to
/*
 **********************************************************************************
 *
 * Video Settings
 *
 **********************************************************************************
 */

// Video Settings
define("import_video", $import_video);
define("brafton_video_publicKey", "$brafton_video_publicKey");
define("brafton_video_secretKey", "$brafton_video_secretKey");

//comment out the video player that are NOT used
//define("video_player", 'atlantis');
//define("video_player", 'videojs');
define("video_player", '$video_player');
define("video_pause_text", '$video_pause_text');
define("video_pause_link", '$video_pause_link');
define("video_end_title", '$video_end_title');
define("video_end_subtitle", '$video_end_subtitle');
define("video_end_button_text", '$video_end_button_text');
define("video_end_button_link", '$video_end_button_link');
EOC;
    $string .= '?>';
    $cred = fopen(HUB_BASE.$client.'/creds.php', 'w');
    $res = fwrite($cred, $string);
    $json = fopen(HUB_BASE.$client.'/creds.json','w');
    fwrite($json,$txtString);
    fclose($json);
    $msg = array();
    if($_POST['hub_client'] == 'new_hub_client'){
        copy(HUB_BASE.'Hubspot-COS/client.php', HUB_BASE.$client.'/client.php');
        chmod(HUB_BASE.$client.'/client.php', 0777);
        chmod(HUB_BASE.$client.'/creds.php', 0666);
        chmod(HUB_BASE.$client.'/creds.json',0666);
        $msg = add_hubspot_cron($client);
        $msg[0] = 'Successfully added '.$client.' to the hubspot importer<br/>'.$msg[0];
    }else{
        $msg[0] = 'Successfully edited '.$client.' on the hubspot importer';
    }
    return $msg;
}

if(isset($_POST['TYPE']) && ($_POST['TYPE'] == 'get_info')){

    $key = $_POST['key'];
    $parms = array('hapikey'=>$key);

    $folders = folder_list($key);

    $blogs = blog_list($parms);
    $authors = author_list($key);
    $returnArray = array(
        'blogs' => $blogs,
        'authors'   => $authors,
        'folders'   => $folders
        );
    $json = json_encode($returnArray);
    echo $json;
}
function cronjob_exists($command){
    $cronjob_exists=false;
    exec('crontab -l', $crontab);
    if(isset($crontab)&&is_array($crontab)){
        $crontab = array_flip($crontab);
        if(isset($crontab[$command])){
            $cronjob_exists=true;
        }
    }
    return $cronjob_exists;
}

function append_cronjob($command){
    if( is_string($command) && !empty($command) && cronjob_exists($command) === FALSE ){
        exec('echo -e "`crontab -l`\n'.$command.'" | crontab -', $output);
        return true;
    }else{
        return false;
    }
}
function add_hubspot_cron($client){
    $msg = array();
    $last = explode(' ',exec('crontab -l'));
    $minute = $last[0];
    if($minute == '*' || $minute == 59){
        $minute = 01;
    }else{
        $minute++;
    }
    $command = $minute." 13-22 * * 1-5 /usr/bin/wget http://tech.brafton.com/hubspot/cos/{$client}/client.php";
    $result = append_cronjob($command);
    if($result){
            $msg[0] = "successfully added $command to the apache cron";
    }else{
        exec('crontab -l', $cron);
        $msg[0] = "Your attempt to add $command to the apache cron has failed as it appears to already exist.  See an output of the existing cron jobs below";
        $msg[1] =$cron;
    }
    return $msg;
}
if(isset($_POST['hub_client'])){
    $msg = add_client();
}

?>