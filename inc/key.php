<?php 
//include('classes/connect.php');
//error_reporting(0);
$upcon = new DBConnect();
$plugin = 'select an importer';
$results = false;
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $key = gen_key();
    $data = array(
        'encryptionKey'     => $key,
        'importer'          => $plugin
    );

    $results = $upcon->insertData('keys', $data);
}
?>
<input type="hidden" name="act" value="edit">
                    <fieldset>
                        <legend>Update Importer</legend>
                        <label>Importers CMS:</label><?php importer_list($plugin); ?><br>
                         <?php if($results){ ?>
                            <h3><?php echo $key; ?></h3>
                        <p>You will Need to send this key for verification to the Error Reporting system to make an error report.<br>
                        May be a GET or POST but must be labeled as "key'</p>
                        <?php } ?>
                    <input type="submit" value="Submit">
