<?php
$root = $_SERVER['HTTP_HOST'];
require_once '../load.php';
$client = $_GET['clientUrl'];
$prots = array(
    '/http:\/\//',
    '/https:\/\//',
    '/www./'
);
$client = preg_replace($prots, '', $client);
$results = $con->retrieveData('log_versions', '*',array("WHERE" => array("domain LIKE '%$client%'")));
$remote = array(
    'client_url'    => $client,
    'date'          => $dateTime,
    'functions'     => $_GET['function']
);
$newRemote = $con->insertData('deployed_remotes', $remote);
if($newRemote){
    $id = $con->get_id();
}
function braftonRemoteImport( $rpc_url, $actions ) {
    global $id;
    global $con;
	$params = array( $actions );
	$request = xmlrpc_encode_request( 'braftonImportRPC', $params );
	$ch = curl_init();
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $request );
    curl_setopt( $ch, CURLOPT_URL, $rpc_url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 0 );
    $data = curl_exec( $ch );
    $con->updateData('deployed_remotes', array('response' => $data, 'response_date' => $dateTime), array(' WHERE ' => array(" id = '$id' ")));
    curl_close( $ch );
    return $data;
}

if(isset($results[0])){
    if(version_compare($results[0]['version'], '3.2.5', '>=')){
        
    }else{
        $version = $results[0]['version'];
        echo "The client at URL $client is currently running a version ($version) that does not support Remote Operation.  Remote Operations require Version 3.2.5 or higher.";
    }
}
else{
    echo 'This client does not appear to be in our system as having installed the Brafton Wordpress Plugin.  Please check your domain url and try again.  If you are sure the client has the most up to date version of the importer plese contact the Updater administrator';

$rpc_server = $_GET['clientUrl'].'/xmlrpc.php';
$actions = array();
$actions = explode(',',$_GET['function']);
$data = braftonRemoteImport( $rpc_server, $actions );
echo $data;
?>