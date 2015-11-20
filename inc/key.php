<?php 
$plugin = 'select an importer';
$results = false;
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $key = gen_key();
    $data = array(
        'encryptionKey'     => $key,
        'importer'          => $plugin
    );

    $results = $con->insertData('keys', $data);
}
?>

<h2>Importers CMS:<?php importer_list($plugin); ?></h2>
<?php if($results){ ?>
<h3 style="color: #000;font-size: 26px;font-weight:900;"><?php echo $key; ?></h3>
<p>You will Need to send this key for verification to the Error Reporting system to make an error report.<br>
May be a GET or POST but must be labeled as "key'</p>
<?php } ?>
