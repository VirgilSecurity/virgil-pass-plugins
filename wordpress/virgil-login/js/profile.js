jQuery(document).ready(function(){
    jQuery('.user-pass1-wrap').hide();
    jQuery('.user-pass2-wrap').hide();

    var virgil = '<tr class="stop-virgil"><th>&nbsp;</th><td>Now you are using Virgil Login. To stop using it and start use normal password, please click on this <a href="javascript:void(0);" id="show-new-password">link</a> and update your password.</td></tr>';
    jQuery('.user-description-wrap').after(virgil);

    jQuery('#show-new-password').click(function() {
        jQuery('#your-profile').append('<input type="hidden" name="stop-using-virgil" value="1" />');
        jQuery('.user-pass1-wrap, .user-pass2-wrap').show();
        jQuery('.stop-virgil').remove();
    })
})