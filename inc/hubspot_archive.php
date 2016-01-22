<?php
require_once '../load.php';
//Get client list
$clients = scandir(HUB_BASE); 
$clientList = array();
foreach($clients as $client){
    if($client != '.' && $client != '..' && $client != 'Hubspot-COS' && is_dir(HUB_BASE.$client)){
        $clientList[] = $client;   
    }
}
//Select Client

//Upload Archive file to "Archives Directory". Rename file as archive-{client_name}
//Run Importer
if(isset($_FILES['archive'])){
    //If file has been uploaded
    $clientName = $_POST['client'];
    echo $clientName;
    $file = $_FILES['archive'];
    $ext = substr($file['name'], -3);
    if($ext != 'xml'){
        echo "You have attempted to upload an unrecognized file format.  You must upload and XML archive downloaded from curator";  
        ?>
        <script>
            function newFrame(){
                window.location = window.location.href;
            }
            window.setTimeout(newFrame, 3000);
        </script>
    <?php
        die();
    }
    
    //Unlink Importer archive file
    $tmp = $file['tmp_name'];
    $status = move_uploaded_file($tmp, HUB_BASE.$clientName.'/archive-'.$clientName.'.xml');
    if($status){
        echo "successfully added archive<br/>";
        echo "Running Importer @ " . HUB_BASE.$clientName.'/client.php<br/>';
        include_once HUB_BASE.$clientName.'/client.php';
    }
    else{
        echo "There was a problem uploading your file. Please try again";
        ?>
        <script>
            setTimeout(window.location.reload(), 2000);
        </script>
<?php
    }
    
}else{ ?>
<!-- HTML GOES HERE -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<h1>Archive Uploader</h1>
    
    <p>You may upload an archive file to a clients Hubspot Importer.  You may than run the clients importer using the archive file by selecting Archive Import and choosing the client on the Main Hupost page.  The archive will automatically be deleted once it is used.</p>
    <label>
        <span>Client</span>
        <select name="client">
            <?php foreach($clientList as $client){ ?>
            <option value="<?php echo $client; ?>"><?php echo $client; ?></option>
            <?PHP } ?>
        </select>
    </label>
    <label>
        <span>Archive</span>
        <input type="file" name="archive">
    </label>
    <input type="submit" value="upload">
</form>
<?php } ?>