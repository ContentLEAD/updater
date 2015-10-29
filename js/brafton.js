$(document).ready(function(){
    $('select[name="sort"]').change(function(e){
        $('form#sorting-form').submit(); 
    });
    $('.delete-individual-error').click(function(e){
        var error = $(this).attr('data-error');
        var input = document.createElement('input');
        input.name = 'error-id[]';
        input.type = 'hidden';
        input.value = error;
        $('form#delete-items').append(input);
        $('#error-'+error).css('background-color','red');
        $(this).css('display', 'none');
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