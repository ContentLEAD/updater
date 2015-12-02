function submit_domains() {
        var domainsInputs = $("#domain-delete").find('input');
    
        $.ajax({
               type: "POST",
               url: "formhandlers/errors.php",
               data: $("#domain-delete").serialize(),
               success: function(data)
               {
                    var domains_deleted = $('#domain-delete input.switch-input').length;
                   
                   var old_total = $('#total_clients').html();
                   var new_total = old_total - domains_deleted;
                   
                   $('#total_clients').html(new_total);
                   $('#domain-delete input.switch-input').map(function(a,e){
                        $(this).detach();
                   });
                   domainsInputs.map(function(a,e){

                        var domain = $(this).val();
                       var domainsTotalErrors = Number($("[id='"+domain+"']").find('.this-domains-errors').html());
                       if(!isNaN(domainsTotalErrors)){
                           
                           var oldTotal = Number($('#total_errors').html()) 
                           
                           var newtotal = (oldTotal * 1) - (domainsTotalErrors * 1);
                           $('#total_errors').html(newtotal);
                           
                       }
                        var cont = $("[id='"+domain+"']");
                       cont.find('input.switch-input').detach();
                       var cHeight = cont.css("height");
                       cont.css("max-height",cHeight);
                       $("[id='"+domain+"']").addClass('delete');
                        $("[id='"+domain+"']").animate({opacity: .01},3000, function(e){
                            $(this).toggle(); 
                        });
                   });                   
               }
        });
    
}
function displayMessage($level, $class, $msg){
            if($("[id='"+$class+"']").length < 1){
                var p = document.createElement('p');
                var text = document.createTextNode($msg);
                p.className = $level+ ' ' + $class;
                p.id = $class;
                p.appendChild(text);
                $('#delete-notice').append(p);
                $('#submit-error-deletion').css({backgroundColor: '#DC5551'});
            }
}
function setscrolling(){
    maxscroll = ($('article.master-display').length - 4) * $('article.master-display').outerWidth();
}
function new_importer(){
if($('input[name="act"]').val() == 'add'){
        var $name = $('input[name="system"]').val();
        $name = $name.replace(/\s/g, '').toLowerCase();
        $.ajax({
            url: "classes/database/tables/plugin_creation.php",
            content: document.body,
            type: 'POST',
            data: 'plugin='+$name
        }).done(function(data){
             if(data == 'success'){
                 $('.administration-settings-form').submit();
             }else{
                 alert('There was an error adding a Table to the database');
                 return false;
             }
        });        
    }   
}
function check_vals(){
    var inputs = $('input');
    for(var i=0;i<inputs.length;++i){
        if($(inputs[i]).val() == undefined || $(inputs[i]).val() == ''){
            if($(inputs[i]).attr('name') == "PASSWORD"){ 
                continue; 
            }
            alert('All Text fields must be filled out to continue');
            $(inputs[i]).css({backgroundColor: 'red'});
            return false;
        }
        $(inputs[i]).css({backgroundColor: 'white'});
    }
    var selects = $('select');
    for(var i=0;i<selects.length;++i){
        if($(selects[i]).val() == undefined || $(selects[i]).val() == ''){
            alert('All Select Drop Downs must be filled out to continue');
            $(selects[i]).css({backgroundColor: 'red'});
            return false;
        }
        $(selects[i]).css({backgroundColor: 'white'});
    }
    return true;
}
$(document).ready(function(){
    $('.administration-settings-form').find('input[type="submit"]').click(function(e){
        e.preventDefault();
        if($('input[name="act"]').val() == 'update' || $('input[name="act"]').val() == 'edit'){
            $('.administration-settings-form').submit();
        }else if($('input[name="act"]').val() == 'add'){
            new_importer();
        }else{
            alert('There appears to be an error.  Please contact your system admin.');
        }
    });
    $('#go-left').click(function(e){
            var width = $('#overflow-container').find('article').outerWidth();
            if(parseInt($('#overflow-container').css("right")) != 0){
                $('#overflow-container').animate({
                    right:"+="+width+"px",
                });
            }
    });
    $('#go-right').click(function(e){
            var width = $('#overflow-container').find('article').outerWidth();
            if(parseInt($('#overflow-container').css("right")) != (maxscroll * -1)){
                $('#overflow-container').animate({
                    right:"-="+width+"px",
                });
            }
    });
    $('article.master-display').map(function(num, e){
        setDisplays = function(e){
            var versionContainerWidth = $('.version-container').outerWidth();
            var articleDisplayWidth = (versionContainerWidth/4) - 2.25;
            var css = $(e).css('background-image');
            $(e).css({minWidth: '210px', maxWidth: articleDisplayWidth+'px'/*, backgroundImage: css*/});
        }
        setDisplays(e);
        $(window).resize(function(){
            $('article.master-display').map(function(num, e){
                setDisplays(e)
            })
        });
        setscrolling();
    });
    $('.instructions').click(function(e){
        var classes = $(this).attr('class').split(' ');
        var disable = classes[1];
        var date = new Date();
        date.setTime(date.getTime()+(60*60*24*30));
        var expire = "; expires="+date.toGMTString();
        document.cookie = disable+"=disable"+expire+"; path=/";
        $('.'+disable).css({display: 'none'});
        e.stopPropagation(); 
    });
    $('.error_list').map(function(num, e){
        var $docHeight = $(window).height();
        var $errorList = $(this);
        var $error = $errorList.find('.error_listing');
        $errorList.css({visibility: 'hidden', display: 'block'});
        var $listHeight = $error.outerHeight();
        var $diff = ($docHeight - $listHeight) / 2;
        $errorList.css({visibility: 'visible', display: 'none'});
        var string = $diff + 'px 12.5%';
        $error.css({margin: string});
    });
    $("span.domain-select input").click( function(e){
        var el = $(this);
        var domain = el.val();
        var pop = $('<div class="pop_up"></div>').appendTo('body').html('<span><h4>'+domain+'</h4>Errors</span>').dialog({
                modal: true,
                title: 'How would you like to Proceed for?',
                text: 'delete for',
                autoOpen: true,
                width: 'auto',
                resizable: false,
                draggable: false,
                dialogClass: 'pop_up',
                width: 350,
                height: 225,
                show: {
                    effect: 'scale'  
                },
                buttons: [
                    {
                        text: "Delete Now",
                        click: function(e){
                            fun = 'now';
                            $(this).dialog("close");
                            
                        }
                    },
                    {
                        text: "Delete After",
                        click: function(e){
                            fun = 'delay';
                            $(this).dialog("close");   
                        }
                    },
                    {
                        text: "Un Selecting",
                        click: function(e){
                            fun = 'unselect';
                            el.attr('checked', false);
                            e.stopImmediatePropagation();
                            $(this).dialog("close");   
                        }
                    }
                ],
                open: function(event, ui){
                    $('.body').toggle("fade");
                    $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();   
                },
                close: function(event, ui){
                    $('.body').toggle("fade");
                    if(fun == 'now'){
                        $('#mass_delete').trigger('click');
                    }else if(fun == 'delay'){
                        
                    }                    
                }
            });            
    });
    
    $(".error_cont").click( function(e){
        $('body').toggleClass('no-scroll');
        $(this).find('div.error_list').slideToggle("slow",function(e){
            var $error = $(this).find('.error_listing');
            $error.click(function(e){
                e.stopPropagation();
            });
        });
    });
    $('select[name="sort"]').change(function(e){
        $('form#sorting-form').submit(); 
    });
    $('.delete-individual-error').click(function(e){
        var error = $(this).attr('data-error');
        var items = $('.deletion-marked');
        var array = items.map(function(a, e){
                return $(this).val();
            });        
        var itemsIndex = $.inArray(error, array);
//        console.log('index is '+itemsIndex);
        if(itemsIndex !== -1){
            items[itemsIndex].remove();
        }else{
            var input = document.createElement('input');
            input.name = 'error-id[]';
            input.type = 'hidden';
            input.className = 'deletion-marked';
            input.value = error;
            $('form#delete-items').append(input);
        }
        displayMessage('warning', 'delete-individual-notice', 'Individual Errors have been marked for deletion');
    });
    $('#submit-error-deletion').click(function(e){
        
        $('#mass_delete').trigger('click');
        $('#delete-items').submit();
    });
    $('#mass_delete').click(function(e){
        $(".domain-select input:checked").clone().appendTo('#domain-delete');
        submit_domains();
    });
    $('.expand').click(function(e){
        var plugin = $(this).attr('id');
        var IdName = plugin;
        plugin = plugin.toLowerCase();
        $.ajax({
            url: "details.php?plugin="+plugin,
            content: document.body
        }).done(function(data){
            var $obj = '.'+IdName+'-table';
            var $back = $($obj).css('background-image');
            $('.importer-details').html(data);            
            var $docHeight = $(window).height();
            var $detailsContainer = $('.importer-details-container');
            var $details = $detailsContainer.find('.importer-details');
            $detailsContainer.css({visibility: 'hidden', display: 'block'});
            var $listHeight = $details.outerHeight();
            var $diff = ($docHeight - $listHeight) / 2;
            $detailsContainer.css({visibility: 'visible', display: 'none'});
            var string = $diff + 'px 12.5%';
            $details.css({margin: string, backgroundImage: $back});
            $('.importer-details-container').toggle();
        });
    });
    $('.add-field').click(function(e){
        var field = '<fieldset class="aField"><label><span>Field Name</span><input type="text" name="fieldName[]"></label><label><span>Default Value</span><input type="text" name="fieldValue[]"></label></fieldset>';
        $('#new_fields').append(field);
    });
    $('select.system').change(function(e){
        var plugin = $(this).val();
        $(location).attr('href',window.location.href+'&plugin='+plugin); 
    });
    $('.importer-details').click(function(e){
        e.stopPropagation(); 
    });
    $('.popUp-info').click(function(e){
        $(this).toggle();
    });
    
});