<?php 
//include('classes/connect.php');
//error_reporting(0);
$upcon = new DBConnect();
$plugin = 'select an importer';
$results = false;
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $bcon = new DBConnect();
    $results = $bcon->retrieveData($plugin, '*',array("ORDER BY" => array("Vid DESC LIMIT 1")));
    $results = $results[0];
}
?>
<input type="hidden" name="act" value="edit">
                    <fieldset>
                        <legend>Update Importer</legend>
                        <label>Importers CMS:</label><?php importer_list($plugin); ?><br>
                        <?php if($results){ ?>
                        <label>Importer Name</label><input type="text" name="IName" value="<?php echo $results['name']; ?>" ><br>
                        <label>Update Date</label><input type="date" name="supported_since" value="<?php echo $date; ?>"><br>
                    </fieldset>
                    <fieldset>
                        <legend>Importer Details</legend>
                        <label>Version #<br></label>
                        <?php $v = explode('.',$results['version']);
                        foreach($v as $key => $val){ ?>
                            <input type="number" name="version[]" value="<?php echo $val; ?>">
                        <?php } ?> <span class="hint-version[]">Please follow established version naming conventions.  <a href="https://docs.google.com/document/d/19fLW1PrUsuvZq49m1fPHV7gX_Tkvftk3NK57QTaDBL0/edit">See Doc</a></span><br>
                        <label>Plugin Description</label><br><label></label><textarea name="description"><?php echo $results['description']; ?></textarea><br>
                        <label>Requires<br><span class="hint-requires"></span></label><input type="text" name="requires" value="<?php echo $results['requires']; ?>"><br>
                        <label>Tested Up to:</label><input type="text" name="tested" value="<?php echo $results['tested']; ?>"><br>
                        <label>Download From<br><span class="hint-download">Specify the complete URL for download from GitHub</span></label><input type="url" name="download" value="<?php echo $results['download_link']; ?>"><br>
                        <label>Importer Version Class<span class="hint-codes">See <a href"https://docs.google.com/a/brafton.com/document/d/1JHfReBDf1dxxS83tgDUtjfIM9T3fR__jSwPtWqXrUrg/edit?usp=sharing">Code List</a> to determine What this Verion should be.</span></label><?php display_code_names($results['code_name']); ?><br>
                        <label>Features/Bugs<br><span class="hint-features">List supported Features added or Bugs fixed</span></label><input type="text" name="features" value="<?php
                        $feats = unserialize($results['features']);
                        $feats = implode(',', $feats); 
                        echo $feats; ?>"><br>
                    </fieldset>
                    <fieldset id="new_fields">
                        <legend>Custom Fields to Add for Automatic Update Control<img src="images/plus.png" class="add-field" title="Add another Field"></legend>
                        <?php 
                        if(!empty($results['custom'])){
                        $featsarray = unserialize($results['custom']);
                        foreach($featsarray as $feature){ ?>
                            <label><?php echo $feature['name']; ?></label><input type="hidden" name="fieldName[]" value="<?php echo $feature['name']; ?>"><input type="text" name="fieldValue[]" value="<?php echo $feature['value']; ?>"><br>
                      <?php } } ?>     
                    </fieldset>
                    <?php } ?>
                    <input type="submit" value="Submit">
