<?php 
if(isset($_POST['codeId'])){
    $update = new DBConnect();
    for($p=0;$p<count($_POST['codeId']);++$p){
        $table = 'codes';
        $the_id = $_POST['codeId'][$p];
        $data = array(
            'active'        => 1,
            'support_added' => $date
        );
        $cond = array(
            'WHERE' => array(
                "id = '$the_id'"));
        $update->updateData($table, $data, $cond);
    }
}
?>

<h2>Active New Naming Convention</h2>
<div class="codenames-activate">
    <input type="hidden" name="codeId" value="on">
<?php 
$con = new DBConnect();
$results = $con->retrieveData('codes', '*');
for($i=0;$i<count($results);++$i){
?>
<label class="codeNames">
    <?php echo $results[$i]['name']; ?>
    <!--
    <input type="hidden" name="codeId[]" value="<?php echo $results[$i]['id']; ?>">
    <select name="active[]">
        <option value="0" <?php if($results[$i]['active'] == 0){ echo 'selected'; } ?>>InActive</option>
        <option value="1" <?php if($results[$i]['active'] == 1){ echo 'selected'; } ?>>Active</option>
    </select>
    -->
    <label class="switch">
        <input type="checkbox" name="codeId[]" class="switch-input" value="<?php echo $results[$i]['id']; ?>" <?php if($results[$i]['active'] == 1){ echo 'checked'; } ?>>
        <span class="switch-label" data-on="Active" data-off="InActive"></span>
        <span class="switch-handle"></span>
    </label>
</label>
<?php } ?>
<input type="submit" value="Submit">
</div>
