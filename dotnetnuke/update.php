<?php
    $results = $con->retrieveData('dotnetnuke', '*', array('ORDER BY'=> array('Vid DESC LIMIT 1')));
    $custom = unserialize($results[0]['custom']);
    $slug[$custom[0]['name']] = $custom[0]['value'];

      $array = new array();
      $obj["new_version"] = $results[0]['version'];;
      $obj["download_link"] = $results[0]['download_link'];
        echo json_encode($obj);
?>