<?php
    $results = $con->retrieveData('dotnetnuke', '*', array('ORDER BY'=> array('Vid DESC LIMIT 1')));
    
      $array = array();
      $array["new_version"] = $results[0]['version'];
      $array["download_link"] = $results[0]['download_link'];
      echo json_encode($array);
?>