<?php
/*
 *
 * Set Database Variables
 */
$creds = $_REQUEST;
$Icreds = "<?php
/* 
 * Credential Interface
 */
interface Icreds{
    const HOST = '{$creds['HOST']}';
    const USER = '{$creds['USER']}';
    const PASS = '{$creds['PASSWORD']}';
    const DB = '{$creds['DATABASE']}';
    const ENV = '{$creds['ENVIORMENT']}';
}
";
$fp=fopen(BASE_PATH.'classes/database/creds.php', 'w');
fwrite($fp, $Icreds);
fclose($fp);
?>