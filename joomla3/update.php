<?php
header("Content-Type: text/xml");
require 'classes/connect.php';
require 'inc/utils.php';
$con = new DBConnect();
    $results = $con->retrieveData('joomla3', '*', array('ORDER BY'=> array('Vid DESC LIMIT 2')));
    $custom[0] = unserialize($results[0]['custom']);
    $custom[1] = unserialize($results[1]['custom']);
foreach($custom[0] as $key=>$value){
    $joomla3[0][$value['name']] = $value['value'];
}
foreach($custom[1] as $key=>$value){
    $joomla3[1][$value['name']] = $value['value'];
}
?>
<updates>
    <?php for($i=0;$i<count($results);$i++){ 
    $maj = (int)explode('.',$results[$i]['requires'])[0];
    $min = (int)explode('.',$results[$i]['requires'])[1];
    $max = (int)explode('.', $results[$i]['tested'])[1];
    $joomlaV = $maj.'.[';
    for($a=$min;$a<$max+1;$a++){
        $joomlaV .= $a;
    }
    $joomlaV .= ']';
    ?>
   <update>
      <name>com_braftonarticles</name>
      <element>com_braftonarticles</element>
      <type>component</type>
      <version><?php echo $results[$i]['version']; ?></version>
      <client>1</client>
      <infourl title="Brafton site">http://brafton.com/support</infourl>
      <downloads>
         <downloadurl type="full" format="zip"><?php echo $results[$i]['download_link']; ?></downloadurl>
      </downloads>
      <tags>
         <tag>stable</tag>
      </tags>
      <maintainer>Brafton</maintainer>
      <maintainerurl>http://brafton.com</maintainerurl>
      <targetplatform name="joomla" version="<?php echo $joomlaV; //$results[$i]['requires']; ?>" />
       <php_minimum><?php echo $joomla3[$i]['php_minimum']; ?></php_minimum>
   </update>
    <?php } ?>
</updates>