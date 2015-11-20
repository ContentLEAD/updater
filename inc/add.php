<input type="hidden" name="act" value="add">
    <h2>Add New Plugin/Module to our Library</h2>
        
        <label>
            <span>Importers CMS</span>
                <input type="text" name="system">
        </label>
        <label>
            <span>Importer Name</span>
                <input type="text" name="IName">
        </label>
        <label>
            <span>Today's Date</span>
            <input type="date" name="supported_since" value="<?php echo $date; ?>">
        </label>
        <label>
            <span>Importer Name</span>
        <input type="file" name="systemLogo">
        </label>
        <h2>Importer Details</h2>
        <label>
            <span>Version #</span>
            <input type="text" name="version">
        </label>
        <label>
            <span>Plugin Description</span>
            <textarea name="description"></textarea>
        </label>
        <label>
            <span>Requires</span>
            <input type="text" name="requires">
        </label>
        <label>
            <span>Tested Up to:</span>
            <input type="text" name="tested">
        </label>
        <label>
            <span>Download From</span>
            <input type="url" name="download">
        </label>
        <label>
            <span>Importer Version Class</span>
            <?php display_code_names(); ?>
        </label>
        <label>
            <span>Features added/Bugs fixed</span>
            <textarea name="features"></textarea>
        </label>
    <fieldset id="new_fields">
        <legend>Custom Fields to Add for Automatic Update Control<i class="fa fa-plus-circle add-field" title="Add another Field"></i><!--<img src="images/plus.png" class="add-field" title="Add another Field">--></legend>    
    </fieldset>
    <input type="submit" value="Submit">
<span class="hint-version">Please follow established version naming conventions.<a href="https://docs.google.com/document/d/19fLW1PrUsuvZq49m1fPHV7gX_Tkvftk3NK57QTaDBL0/edit">See Doc</a></span>
