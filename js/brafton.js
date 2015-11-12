function submit_domains() {
        var domainsInputs = $("#domain-delete").find('input');
        $.ajax({
               type: "POST",
               url: "formhandlers/errors.php",
               data: $("#domain-delete").serialize(), // serializes the form's elements.
               success: function(data)
               {
                   domainsInputs.map(function(a,e){
                        var domain = $(this).val()
                        var cont = $("[id='"+domain+"']");
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
$(document).ready(function(){
    
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
    $("span.domain-select").click( function(e){
        var el = $(this);
        var domain = el.find('input').val();
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
                            if(!el.data('selected') || el.data('selected') === undefined ){
                                el.data('selected', true);
                                el.find('input').attr('checked', true);
                            }
                            $('#mass_delete').trigger('click');
                            $(this).dialog("close");
                            
                        }
                    },
                    {
                        text: "Add to Batch",
                        click: function(e){
                            if(!el.data('selected') || el.data('selected') === undefined ){
                                el.data('selected', true);
                                el.find('input').attr('checked', true);
                                el.find('span').toggleClass('selected');
                            }
                            $(this).dialog("close");   
                        }
                    },
                    {
                        text: "Un Select",
                        click: function(e){
                            el.data('selected', false);
                            el.find('input').attr('checked', false);
                            el.find('span').removeClass('selected');
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
        console.log('index is '+itemsIndex);
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
        if($('#delete-notice').css('display') == 'none'){
            $('#delete-notice').toggle();
            var p = document.createElement('p');
            var text = document.createTextNode('You have marked items for deletion from the database.  Be sure to click "Delete Selection" at the bottom of the page before leaving');
            p.appendChild(text);
            $('#delete-notice').append(p);
        }
    });
    $('#submit-error-deletion').click(function(e){
        $('#delete-items').submit();
    });
    $('#mass_delete').click(function(e){
        $(".domain-select input:checked").clone().appendTo('#domain-delete');
        submit_domains();
    });
    $('.expand').click(function(e){
        var plugin = $(this).attr('id');
        plugin = plugin.toLowerCase();
        $.ajax({
            url: "details.php?plugin="+plugin,
            content: document.body
        }).done(function(data){
            $('.importer-details').html(data);
            $('.importer-details').toggle();
            $('.importer-details-close').toggle();
        });
    });
    $('.importer-details-close').click(function(e){
        $('.importer-details').toggle();
        $('.importer-details-close').toggle();
    });
    $('input').focus(function(e){
        var hint = 'hint-'+$(this).attr('name');
        $('.'+hint).toggle();
    });
    $('.add-field').click(function(e){
        var field = '<fieldset class="aField"><legend>Field Name<input type="text" name="fieldName[]"></legend><label>Default Value</label><input type="text" name="fieldValue[]"><br></fieldset>';
        $('#new_fields').append(field);
    });
    $('select.system').change(function(e){
        var plugin = $(this).val();
        $(location).attr('href',window.location.href+'&plugin='+plugin); 
    });
});

function check_vals(){
    var inputs = $('input');
    for(var i=0;i<inputs.length;++i){
        if($(inputs[i]).val() == undefined){
            alert('All Text fields must be filled out to continue');
            alert($(inputs[i]).attr('name'));
            return false;
        }
    }
    var selects = $('select');
    for(var i=0;i<selects.length;++i){
        if($(selects[i]).val() == undefined){
            alert('All Select Drop Downs must be filled out to continue');
            return false;
        }
    }
    return true;
}