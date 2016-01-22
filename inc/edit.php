<?php 
$plugin = 'select an importer';
$results = false;
if(isset($_GET['plugin'])){
    $plugin = $_GET['plugin'];
    $results = $con->retrieveData($plugin, '*',array("ORDER BY " => array("Vid DESC LIMIT 1")));
    $results = $results[0];
    
}
?>
<input type="hidden" name="act" value="edit">
    <h2>Importers CMS <?php importer_list($plugin); ?></h2>
        <?php if($results){ ?>
        <label>
            <span>Importer Name</span>
                <input type="text" name="IName" value="<?php echo $results['name']; ?>" >
        </label>
        <label>
            <span>Today's Date</span>
            <input type="date" name="supported_since" value="<?php echo $date; ?>">
        </label>
        <h2>Importer Details</h2>
        <label>
            <span>Version #</span>
            <?php $v = explode('.',$results['version']);
                while(count($v) <= 3){
                    array_push($v, '0');   
                }
                foreach($v as $key => $val){ ?>
                <input type="number" name="version[]" value="<?php echo $val; ?>">
            <?php } ?>
        </label>
        <label>
            <span>Plugin Description</span>
            <textarea name="description"><?php echo $results['description']; ?></textarea>
        </label>
        <label>
            <span>Requires</span>
            <input type="text" name="requires" value="<?php echo $results['requires']; ?>">
        </label>
        <label>
            <span>Tested Up to:</span>
            <input type="text" name="tested" value="<?php echo $results['tested']; ?>">
        </label>
        <label>
            <span>Download From</span>
            <input type="url" name="download" value="<?php echo $results['download_link']; ?>">
        </label>
        <label>
            <span>Importer Version Class</span>
            <?php display_code_names($results['code_name']); ?>
        </label>
        <label>
            <span>Features added/Bugs fixed</span>
            <input type="text" name="features" value="<?php
                        $feats = unserialize($results['features']);
                        $feats = implode(',', $feats); 
                        echo $feats; ?>">
        </label>
    <fieldset id="new_fields">
        <legend>Custom Fields to Add for Automatic Update Control<i class="fa fa-plus-circle add-field" title="Add another Field"></i><!--<img src="images/plus.png" class="add-field" title="Add another Field">--></legend> 
        <?php 
        if(!empty($results['custom'])){
        $featsarray = unserialize($results['custom']);
        foreach($featsarray as $feature){ ?>
            <label><?php echo $feature['name']; ?></label><input type="hidden" name="fieldName[]" value="<?php echo $feature['name']; ?>"><input type="text" name="fieldValue[]" value="<?php echo $feature['value']; ?>"><br>
      <?php } } ?> 
    </fieldset>
    <input type="submit" value="Submit">
    <?php } ?>
