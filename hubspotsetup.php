<?php 
include 'load.php';
define("HUB_BASE", "/var/www/html/tech/hubspot/test/");
include BASE_PATH.'inc/setupFunctions.php';
$type = 'new';
$clients = scandir(HUB_BASE); 
$clientList = array();
foreach($clients as $client){
    if($client != '.' && $client != '..' && $client != 'Hubspot-COS' && is_dir(HUB_BASE.$client)){
        $clientList[] = $client;   
    }
}
if(isset($_POST['edit_hub_client'])){
    $filename = HUB_BASE.$_POST['clients_hub_edit'].'/creds.json';
    $json = fopen($filename, 'r');
    $data = fread($json,filesize($filename));
    $data = json_decode($data);
    $type = 'edit';
}
$client_total = count($clientList);
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8">
    <title>Brafton Update &amp; Error API Installation</title>
	<meta name="Author" content=""/>
    <?php include_once BASE_PATH.'inc/scripts.php'; ?>
</head>
<body>
    <header class="top">
                <?php include BASE_PATH.'inc/nav.php'; ?>
            </header>
        <div class="body">
            <section class="main-container">
               <?php get_header('Brafton Plugin and Module Version Control', 'Installation Wizard'); ?>
                <div class="install-instructions hubspot">
                    <h2>Setup a clients Hubspot Importer</h2>
                    <p>Currently you will need to set up a cron task through shell access when your done.</p>
                    <div style="clear:both;">Total Hubspot-COS Client: <?php echo $client_total; ?></div>
                </div>
                <form action="" method="post" style="width:40%;float:left" class="hubclient-form">
                <label>
                    <span>Edit a Clients Creds</span>
                    <select id="clients_hub_edit" name="clients_hub_edit">
                        <option>Choose a Client</option>
                        <?php foreach($clientList as $client){ ?>
                        <option value="<?php echo $client; ?>"><?php echo $client; ?></option>
                        <?php } ?>
                    </select>
                </label>
                <label>
                   <!-- <input type="submit" value="Edit"> -->
                    <input type="hidden" name="edit_hub_client" value="edit">
                </label>    
                    <label title="This will output complete details during the import run.">
                    <span>Run an Importer</span><input type="checkbox" id="debug" value="true">DEBUG MODE
                    <select id="clients_hub">
                        <option>Choose a Client</option>
                        <?php foreach($clientList as $client){ ?>
                        <option value="<?php echo $client; ?>"><?php echo $client; ?></option>
                        <?php } ?>
                    </select>
                </label>
                </form>
            <form id="hub-setup" class="hubspot-setup-form" method="post" action="" onsubmit="return checkVitals();">
            
                <label>
                    <span>Client Domain</span>
                    <input type="text" name="client" <?php if(isset($data)){ ?>value="<?php echo $data->client; ?>" <?php } ?>>
                </label>

                <label>
                    <span>Domain</span>
                    <select name="domain">
                        <option value="">Select API Domain</option>
                        <option value="http://api.brafton.com" <?php if(isset($data)){ matchOptions($data->domain,'http://api.brafton.com', 'selected'); } ?>>Brafton</option>
                        <option value="http://api.contentlead.com" <?php if(isset($data)){ matchOptions($data->domain,'http://api.contentlead.com', 'selected'); } ?>>ContentLEAD</option>
                        <option value="http://api.castleford.com.au" <?php if(isset($data)){ matchOptions($data->domain,'http://api.castleford.com.au', 'selected'); } ?>>Castleford</option>
                    </select>
                </label>
                <label class="item-container">
                    <span>Brafton API Key</span>
                    <input type="text" name="brafton_apiKey" class="key" <?php if(isset($data)){ ?>value="<?php echo $data->brafton_apiKey; ?>" <?php } ?>>
                </label>
                <label>
                    <span>Hubspot API Key</span>
                    <input type="text" name="hub_apiKey" id="hub_apiKey" <?php if(isset($data)){ ?>value="<?php echo $data->hub_apiKey; ?>" <?php } ?>>
                </label>
                <label>
                    <span>Portal ID</span>
                    <input type="text" name="portal" <?php if(isset($data)){ ?>value="<?php echo $data->portal; ?>" <?php } ?>>
                </label>
                <label>
                <input type="button" class="get-info" id="get-info" value="Retieve Blog Info" ><span class="tip">Click here to get Blog Id, Author Id's and Image Folders</span>
                </label>

                <label>
                    <span>Blog Id</span>
                    <select id="blogs" name="blog_id">
                    </select>
                    <span class="old-val"><?php if(isset($data)){ echo $data->blog_id; } ?></span>
                </label>
                <label>
                    <span>Post Status</span>
                    Published<input type="radio" name="post_status" value="published" <?php if(isset($data)){ matchOptions($data->post_status,'published', 'checked'); } ?>>Draft<input type="radio" name="post_status" value="draft" <?php if(isset($data)){ matchOptions($data->post_status,'draft', 'checked'); } ?>>
                </label>
                <label>
                    <span>Dynamic Author</span>
                    TRUE<input type="radio" name="dynamic_author" value="true" <?php if(isset($data)){ matchOptions($data->dynamic_author,'true', 'checked'); } ?>>FALSE<input type="radio" name="dynamic_author" value="false" <?php if(isset($data)){ matchOptions($data->dynamic_author,'false', 'checked'); } ?>>
                </label>
                <label>
                    <span>Author_id</span>
                    <select id="authors" name="author_id">
                    </select>
                    <span class="old-val"><?php if(isset($data)){ echo $data->author_id; }?></span>
                </label>
                <label>
                    <span>Image Upload</span>
                    TRUE<input type="radio" name="image_import" value="true" <?php if(isset($data)){ matchOptions($data->image_import,'true', 'checked'); } ?>>FALSE<input type="radio" name="image_import" value="false" <?php if(isset($data)){ matchOptions($data->image_import,'false', 'checked'); } ?>>
                </label>
                <label>
                    <span>Image Location</span>
                    <select id="folders" name="image_folder">
                    </select><span class="old-val"><?php if(isset($data)){ echo $data->image_folder; } ?></span>
                </label>
                <label>
                    <span>Video Import</span>
                    TRUE<input type="radio" name="import_video" value="true" <?php if(isset($data)){ matchOptions($data->import_video,'true', 'checked'); } ?>>FALSE<input type="radio" name="import_video" value="false" <?php if(isset($data)){ matchOptions($data->import_video,'false', 'checked'); } ?>>
                </label>
                <label>
                    <span>Public Key</span>
                    <input type="text" name="brafton_video_publicKey" <?php if(isset($data)){ ?>value="<?php echo $data->brafton_video_publicKey; ?>" <?php } ?>>
                </label>
                <label>
                    <span>Private Key</span>
                    <input type="text" name="brafton_video_secretKey" <?php if(isset($data)){ ?>value="<?php echo $data->brafton_video_secretKey; ?>" <?php } ?>>
                </label>
                <label>
                    <span>Video Player</span>
                    <select name="video_player">
                        <option value="atlantisjs" <?php if(isset($data)){ matchOptions($data->video_player,'atlantisjs', 'selected'); } ?>>Atlantis JS</option>
                        <option value="videojs" <?php if(isset($data)){ matchOptions($data->video_player,'videojs', 'selected'); } ?>>Video JS</option>
                    </select>
                </label>
                <h2>Video CTA's</h2>
                <label>
                    <span>Pause Text</span>
                    <input type="text" name="video_pause_text" <?php if(isset($data)){ ?>value="<?php echo $data->video_pause_text; ?>" <?php } ?>>
                </label>
                <label>
                    <span>Pause Link</span>
                    <input type="text" name="video_pause_link" <?php if(isset($data)){ ?>value="<?php echo $data->video_pause_link; ?>" <?php } ?>>
                </label>
                <label>
                    <span>End Title</span>
                    <input type="text" name="video_end_title" <?php if(isset($data)){ ?>value="<?php echo $data->video_end_title; ?>" <?php } ?>>
                </label>
                <label>
                    <span>End Subtitle</span>
                    <input type="text" name="video_end_subtitle" <?php if(isset($data)){ ?>value="<?php echo $data->video_end_subtitle; ?>" <?php } ?>>
                </label>
                <label>
                    <span>End Button Text</span>
                    <input type="text" name="video_end_button_text" <?php if(isset($data)){ ?>value="<?php echo $data->video_end_button_text; ?>" <?php } ?>>
                </label>
                <label>
                    <span>End Button Link</span>
                    <input type="text" name="video_end_button_link" <?php if(isset($data)){ ?>value="<?php echo $data->video_end_button_link; ?>" <?php } ?>>
                </label>
                <input type="hidden" name="hub_client" value="<?php echo $type; ?>_hub_client">
                <label>
                    <input type="submit" value="<?php echo $type; ?> Client">
                </label>
            </form>
            <iframe class="results" src=""></iframe>
            </section>
        </div>
<script>
        function checkVitals(){
            var val = true;
            if(($("input[name='post_status']:checked").length == 0) || ($("input[name='dynamic_author']:checked").length == 0) || ($("input[name='image_import']:checked").length == 0) || ($("input[name='import_video']:checked").length == 0)){
                val = false;
                alert('Please be sure the following fields are filled out.\n Post Status \n Dynamic Author \n Image Upload \n Video Import');
            }
            return val;   
        }
    $(document).ready(function(){
        $('#clients_hub_edit').change(function(e){
            $('.hubclient-form').submit();
        });
        $('#get-info').click(function(){
            var hubKey = $('#hub_apiKey').val();
            $.ajax({
                url: "setupFunctions.php",
                method: "POST",
                data: { key: hubKey, TYPE: 'get_info'}                
            }).done(function(data){
                var obj = $.parseJSON(data);
                var blogs = obj['blogs'];
                var authors = obj['authors'];
                var folders = obj['folders'];
                $('#blogs').html(blogs);
                $('#authors').html(authors);
                $('#folders').html(folders);
            });
        });
        $('#clients_hub').change(function(e){
            var client = $(this).val();
            var debug = '';
            var a = $('#debug');
            if(a.is(':checked')){
                debug = '?debug=true';   
            }
            $('iframe.results').attr("src", "http://tech.brafton.com/hubspot/cos/"+client+'/client.php'+debug);
            $(this).val('');
        });
        <?php if(isset($data)){ ?>
                $(document).ready(function(){
                   $('#get-info').trigger('click');
                });
             <?php   }
        ?>
    });
    </script>
</body>
</html>
