    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <style>
        select{
                width: 250px;
                display: block;
                position: relative;
                left: 260px;
                top: -35px;
            height:auto;
        }
        label{
            clear:both;
            margin:15px 5px;
            padding:5px;
        }
        #Importer-results{
            margin:25px auto;
            width:50%;
            position:relative;
        }
        #Importer-results div:nth-of-type(1){
            bottom:0px !important;
            left:0px !important;
            width:100% !important;
            position:relative !important;
        }
        #Importer-results div:nth-of-type(2){
            bottom:0px !important;
            left:0px !important;
            width:100% !important;
            position:relative !important;
        }
    </style>
        <form class="ui-widget">
            <label>Client Wordpress Url<span style="font-size:.75em;display:block;">Url of the clients Wordpress installation.</span></label><input type="url" name="clientUrl" id="clientUrl"><br/>
            <label>Functions<br><span style="font-size:.75em;display:block;">You can select multiple functions at once.  they are performed from top to bottom</span></label><select name="function" size="4" multiple id="function">
                <option>articles</option>
                <option>videos</option>
                <option>get_options</option>
                <option>get_errors</option>
            </select><br/>
            <input type="button" value="Deploy Remote" id="deploy">
        </form>
        <div id="Importer-results">
        
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
