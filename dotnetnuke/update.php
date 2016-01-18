<?php
    $results = $con->retrieveData('dotnetnuke', '*', array('ORDER BY'=> array('Vid DESC LIMIT 1')));
    echo '<pre>';var_dump($results);
      //$array = new array();
      //$obj["new_version"] = $results[0]['version'];;
      //$obj["download_link"] = $results[0]['download_link'];
       // echo json_encode($obj);
?>