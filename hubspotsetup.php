<?php 
include 'load.php';
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
               <?php get_header('Brafton Plugin and Module Version Control', 'Hubspot Setup'); ?>
                <?php if(isset($devMessage)){ echo $devMessage; } ?>
                <div class="install-instructions hubspot">
                    <h2>Setup a clients Hubspot Importer</h2>
                    <p>Running a hubspot importer now shows results in this screen. A Cron job will automatically be added to the system for the Apache user on the tech server.</p>
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
                    <span>Run an Importer</span><input type="checkbox" id="debug" value="true">DEBUG MODE<input type="checkbox" id="archive_import" value="true" style="float:none">ARCHIVE IMPORT
                    <select id="clients_hub">
                        <option>Choose a Client</option>
                        <?php foreach($clientList as $client){ ?>
                        <option value="<?php echo $client; ?>"><?php echo $client; ?></option>
                        <?php } ?>
                    </select>
                </label>
                    <span id="archive-loader" class="button archive">Upload Archive</span>
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
                    <input type="hidden" class="blogs-old-val" value="<?php if(isset($data)){ echo $data->blog_id; } ?>">
                </label>
                <label>
                    <span>Post Status</span>
                    Published<input type="radio" name="post_status" value="published" <?php if(!isset($data)){ echo 'checked'; }else if(isset($data)){    matchOptions($data->post_status,'published', 'checked'); $published_default = true;} ?>>Draft<input type="radio" name="post_status" value="draft" <?php if(isset($data)){ matchOptions($data->post_status,'draft', 'checked'); $published_default = true;} ?>>
                </label>
                <label>
                    <span>Dynamic Author</span>
                    TRUE<input type="radio" name="dynamic_author" value="true" <?php if(isset($data)){ matchOptions($data->dynamic_author,'true', 'checked'); } ?>>FALSE<input type="radio" name="dynamic_author" value="false" <?php if(!isset($data)){ echo 'checked'; } else if(isset($data)){ matchOptions($data->dynamic_author,'false', 'checked'); } ?>>
                </label>
                <label>
                    <span>Author_id</span>
                    <select id="authors" name="author_id">
                    </select>
                    <input type="hidden" class="authors-old-val" value="<?php if(isset($data)){ echo $data->author_id; }?>">
                </label>
                <label>
                    <span>Image Upload</span>
                    TRUE<input type="radio" name="image_import" value="true" <?php if(isset($data)){ matchOptions($data->image_import,'true', 'checked'); } ?>>FALSE<input type="radio" name="image_import" value="false" <?php if(!isset($data)){ echo 'checked'; }else if(isset($data)){ matchOptions($data->image_import,'false', 'checked'); } ?>>
                </label>
                <label>
                    <span>Image Location</span>
                    <select id="folders" name="image_folder">
                    </select>
                    <input type="hidden" class="folder-old-val" value="<?php if(isset($data)){ echo $data->image_folder; } ?>">
                </label>
                <label>
                    <span>Video Import</span>
                    TRUE<input type="radio" name="import_video" value="true" <?php if(isset($data)){ matchOptions($data->import_video,'true', 'checked'); } ?>>FALSE<input type="radio" name="import_video" value="false" <?php if(!isset($data)){ echo 'checked'; }else if(isset($data)){ matchOptions($data->import_video,'false', 'checked'); } ?>>
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
                        <option value="atlantisjs" <?php if(!isset($data)){ echo 'checked'; }else if(isset($data)){ matchOptions($data->video_player,'atlantisjs', 'selected'); } ?>>Atlantis JS</option>
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
                <?php if(isset($msg) && is_array($msg)){ ?>
                <div class="results" style="float: right;width: 50%;overflow-wrap: break-word;background-color: #FFF;padding: 5px;box-sizing: border-box;">
                    <pre class="notice">
                        <?php if(!isset($msg[1])){
                                echo $msg[0];
                        }else{
                                var_dump($msg); 
                        } ?>
                    </pre>
                </div>
                <?php } ?>
            <iframe class="results" src=""><?php if(isset($devMessage)){ echo $devMessage; } ?></iframe>
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
        $('#archive-loader').click(function(e){
            $('#archiver').toggle();
            var frame = $('#archiver iframe');
            var src = frame.attr('src');
            frame.attr('src', src)
        });
        $('.close-button').click(function(e){
            var parent = $(this).parent();
            parent.toggle();
        });
        $('#clients_hub_edit').change(function(e){
            $('.hubclient-form').submit();
        });
        $('#get-info').click(function(){
            var hubKey = $('#hub_apiKey').val();
            $.ajax({
                url: "../load.php",
                method: "POST",
                data: { key: hubKey, TYPE: 'get_info'}                
            }).done(function(data){
                var obj = $.parseJSON(data);
                var blogs = obj['blogs'];
                var authors = obj['authors'];
                var folders = obj['folders'];
                $('#blogs').html(blogs);
                var old_blog = $('.blogs-old-val').val();
                if(old_blog != '' && old_blog != undefined){
                    $('#blogs option[value="'+old_blog+'"]').attr('selected', 'selected');  
                }
                $('#authors').html(authors);
                var old_authors = $('.authors-old-val').val();
                if(old_authors != '' && old_authors != undefined){
                    $('#authors option[value="'+old_authors+'"]').attr('selected', 'selected');    
                }
                $('#folders').html(folders);
                var old_folders = $('.folders-old-val').val();
                if(old_folders != '' && old_folders != undefined){
                    $('#folders option[value="'+old_folders+'"]').attr('selected', 'selected');    
                }
            });
        });
        $('#clients_hub').change(function(e){
            var client = $(this).val();
            var debug = '';
            var archive = '';
            var urlArray = [];
            var a = $('#debug');
            if(a.is(':checked')){
                debug = 'debug=true'; 
                urlArray.push(debug);
            }
            var b = $('#archive_import');
            if(b.is(':checked')){
                archive = 'archive=true'; 
                urlArray.push(archive);
            }
            var urlString = urlArray.join('&');
            console.log(urlString);
            $('iframe.results').attr("src", "http://tech.brafton.com/hubspot/cos/"+client+'/client.php?'+urlString);
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
    <div id="archiver" style="position:absolute; top:0;left:0;width:100%;height:100%;padding:10%;background-color:white;display:none"><span class="button close-button"></span><iframe src="/inc/hubspot_archive.php"  style="display:block;width:75%;margin:0px auto; height:75%;"></iframe></div>
</body>
</html>
