<?php
header("Content-Type: text/xml");
require '../classes/connect.php';
require '../inc/utils.php';

$con = new DBConnect();
    $results = $con->retrieveData('drupal8', '*', array('ORDER BY'=> array('Vid DESC LIMIT 2')));
    $custom[0] = unserialize($results[0]['custom']);
    $custom[1] = unserialize($results[1]['custom']);
foreach($custom[0] as $key=>$value){
    $drupal[0][$value['name']] = $value['value'];
}
foreach($custom[1] as $key=>$value){
    $drupal[1][$value['name']] = $value['value'];
}
/*
echo '<pre>';
var_dump($custom);
echo '</pre>';
echo '<pre>';
var_dump($drupal);
echo '</pre>';
$dump = $results;
echo '<pre>';
var_dump($dump);
echo '</pre>';
*/
$dec = <<<DEC
<?xml version="1.0" encoding="utf-8"?>
DEC;
?>
<?php //echo $dec; ?>
<project xmlns:dc="http://purl.org/dc/elements/1.1/">
<title>brafton</title>
<short_name>brafton</short_name>
<dc:creator>Brafton</dc:creator>
<type>project_module</type>
<api_version>8.x</api_version>
<project_status>published</project_status>
<link>http://www.brafton.com/support/drupal-8</link>
  <terms>
   <term><name>Projects</name><value>Modules</value></term>
  </terms>
<releases>
    <?php for($i=0;$i<count($results);$i++){ ?>
        <release>
  <name>brafton<?php echo $results[$i]['requires'].'-'.$results[$i]['version']; ?></name>
  <version><?php echo $results[$i]['requires'].'-'.$results[$i]['version']; ?></version>
  <tag><?php echo $results[$i]['requires'].'-'.$results[$i]['version']; ?></tag><?php $versions = explode('.',$results[$i]['version']); ?>
  <version_major><?php echo $versions[0]; ?></version_major>
  <version_patch><?php echo $versions[1].$versions[2]; ?></version_patch>
  <status>published</status>
  <release_link><?php echo $drupal[$i]['release_link']; ?></release_link>
  <download_link><?php echo $results[$i]['download_link']; ?></download_link>
<date>1446661740</date>
<mdhash>4d78ec1e9b7ab2dfe9cb08e3b4cd386b</mdhash>
<filesize>90325</filesize>
<files>
<file>
<url><?php echo $results[$i]['download_link']; ?></url>
<archive_type>zip</archive_type>
<md5>4d78ec1e9b7ab2dfe9cb08e3b4cd386b</md5>
<size>90325</size>
<filedate>1390351105</filedate>
</file>
</files>
  <terms>
   <term><name>Release type</name><value><?php echo $drupal[$i]['release_type']; ?></value></term>
  </terms>
 </release>
    <?php } ?>
</releases>
</project>
