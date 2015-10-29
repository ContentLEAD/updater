<input type="hidden" name="act" value="add">
                    <fieldset>
                        <legend>Add New Importer to our Library</legend>
                        <label>Importers CMS</label><input type="text" name="system"><br>
                        <label>Importer Name</label><input type="text" name="IName"><br>
                        <label>Today's Date</label><input type="date" name="supported_since"><br>
                    </fieldset>
                    <fieldset>
                        <legend>Importer Details</legend>
                        <label>Version #<br><span class="hint-version">Please follow established version naming conventions.  <a href="https://docs.google.com/document/d/19fLW1PrUsuvZq49m1fPHV7gX_Tkvftk3NK57QTaDBL0/edit">See Doc</a></span></label><input type="text" name="version"><br>
                        <label>Plugin Description</label><br><label></label><textarea name="description"></textarea><br>
                        <label>Requires<br><span class="hint-requires"></span></label><input type="text" name="requires"><br>
                        <label>Tested Up to:</label><input type="text" name="tested"><br>
                        <label>Download From<br><span class="hint-download">Specify the complete URL for download from GitHub</span></label><input type="url" name="download"><br>
                        <label>Importer Version Class<span class="hint-codes">See <a href"https://docs.google.com/a/brafton.com/document/d/1JHfReBDf1dxxS83tgDUtjfIM9T3fR__jSwPtWqXrUrg/edit?usp=sharing">Code List</a> to determine What this Verion should be.</span></label><?php display_code_names(); ?><br>
                        <label>Features/Bugs<br><span class="hint-features">List Known Bugs that exsist (no one is perfect) </span></label><input type="text" name="features"><br>
                    </fieldset>
                    <fieldset id="new_fields">
                        <legend>Custom Fields to Add for Automatic Update Control<img src="images/plus.png" class="add-field" title="Add another Field"></legend>    
                    </fieldset>
                    <input type="submit" value="Submit">