<?php 
//include('classes/connect.php');
//error_reporting(0);
if(isset($_POST['codeId'])){
    $update = new DBConnect();
    for($p=0;$p<count($_POST['codeId']);++$p){
        $table = 'codes';
        $the_id = $_POST['codeId'][$p];
        $data = array(
            'active'        => $_POST['active'][$p],
            'support_added' => $date
        );
        $cond = array(
            'WHERE' => array(
                "id = '$the_id'"));
        $update->updateData($table, $data, $cond);
    }
}
?>
                    <fieldset>
                        <legend>Active New Naming Convention</legend>
                        <?php 
                        $con = new DBConnect();
                        $results = $con->retrieveData('codes', '*');
                        for($i=0;$i<count($results);++$i){
                        ?>
                        <label><?php echo $results[$i]['name']; ?></label><input type="hidden" name="codeId[]" value="<?php echo $results[$i]['id']; ?>"><select name="active[]"><option value="0" <?php if($results[$i]['active'] == 0){ echo 'selected'; } ?>>InActive</option><option value="1" <?php if($results[$i]['active'] == 1){ echo 'selected'; } ?>>Active</option></select>
                        <?php } ?>
                    <input type="submit" value="Submit">
