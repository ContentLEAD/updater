    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <form class="ui-widget remotes">
            <h2>Wordpress Remote</h2>
            <p>This remote can make perform actions on a clients install of wordpress.  The url must be the full url path to the wordpress installation. 
            <span class=""></span></p>
            <label>
                <span style="width:100%;">Url of Installation</span>
                <input type="url" name="clientUrl" id="clientUrl" style="clear:both; width:100%">
            </label>
            <label>
                <span>Actions</span>
                <select name="function" size="4" multiple id="function" style="clear:both; width:100%">
                    <option>articles</option>
                    <option>videos</option>
                    <option>get_options</option>
                    <option>get_errors</option>
                </select>
            </label>
            <label>
                <span></span>
            <input type="button" value="Deploy" id="deploy">
            </label>
        </form>
        <div id="Importer-results" class="remote-results">
        
        </div>
    <script>
        $('#deploy').click(function(e){
            var url = $('#clientUrl').val();
            var actions = $('#function').val().join(",");
            console.log(actions);
            $.ajax({
                url: "wp-remote/remote.php?clientUrl="+url+"&function="+actions,
            }).done(function(data){
                $('#Importer-results').html(data);
                var close = $('#close-imported');
                console.log(close);
                $('.close-imported').click(function(e){
                    var div = $(this).parent();
                    div.remove();
                });
            });
        });
    </script>
