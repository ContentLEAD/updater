<?php
require 'classes/connect.php';
require 'inc/utils.php';
//wordpress return for plugin update information
if (isset($_REQUEST['action'])) {
    $con = new DBConnect();
    $results = $con->retrieveData('wordpress', '*', array('ORDER BY'=> array('Vid DESC LIMIT 1')));
    $custom = unserialize($results[0]['custom']);
    $slug[$custom[0]['name']] = $custom[0]['value'];
    //retrieve changelog
    $changelog = $con->retrieveData('wordpress', '*', array('ORDER BY' => array('Vid DESC')));
    //build changelog string
    $log = '';
    foreach($changelog as $entry){
        $features = unserialize($entry['features']);
        $log .= '<h4>Version '.$entry['version'].'</h4><em>Release:'. date('M d, Y',strtotime($entry['last_updated'])).'</em><ul>';
        foreach($features as $feat){
            $log .= '<li>'.$feat.'</li>';
        }
        $log .= '</ul>';
    }
    $log .= '';
  switch ($_REQUEST['action']) {
    case 'version':
      echo $results[0]['version'];
      break;
    case 'info':
      $brand = $_REQUEST['brand'];
      switch($brand){
        case 'api.brafton.com':
          $brand = 'banner_brafton';
          $homepage = 'brafton.com';
          break;
        case 'api.contentlead.com':
          $brand = 'banner_contentlead';
          $homepage = 'contentlead.com';
          break;
        case 'api.castleford.com.au':
          $brand = 'banner_castleford';
          $homepage = 'castleford.com.au';
          break;
      }
      $low = 'http://updater.brafton.com/images/'.$brand.'.jpg';
      $high = 'http://updater.brafton.com/images/'.$brand.'.jpg';
      $obj = new stdClass();
      $obj->slug = $slug['slug'];
      $obj->plugin_name = $results[0]['name'];
      $obj->new_version = $results[0]['version'];;
      $obj->requires = $results[0]['requires'];
      $obj->tested = $results[0]['tested'];
      $obj->downloaded = 97647;
      $obj->last_updated = $results[0]['last_updated'];
      $obj->sections = array(
        'description' => $results[0]['description'], 
          'services'    => 'this section can display the services we offer',
          'changelog'   => htmlentities($log)
      );
      $obj->banners = array(
          'low'     => $low,
          'high'    => $high
      );
      $obj->homepage = $homepage;
      $obj->package = $results[0]['download_link'];
      $obj->download_link = $results[0]['download_link'];
      echo serialize($obj);
      /*
      echo '<pre>';
      var_dump($obj);
      */
      break;
      
      //not using licensing
    case 'register':
        $url = $_REQUEST['domain']
        $client = parse_url($url)['host']; 
        $version = $_REQUEST['version'];
      $api = $_REQUEST['api'];
      $brand = $_REQUEST['brand'];
      
        if($lid = $con->retrieveData('log_versions', '*', array("WHERE" =>  array(" domain='$client'")))){
          $lid = $lid[0]['lid'];
        $con->updateData('log_versions', array('site_url' => $url, 'importer' => 'wordpress', 'version' => $version, 'api' => $api, 'brand' => $brand), array("WHERE" => array(" lid='$lid'")));
            echo "$client upgraded successfully and has now registed their api key and brand";
        }else{
         $con->insertData('log_versions', array('domain' => $client, 'site_url' => $url, 'importer' => 'wordpress', 'version' => $version));
            echo "$client registered successfully as new client";
        }
      break;

  }
}
?>