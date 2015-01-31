jQuery(document).ready(function(){
    jQuery('.user-pass1-wrap').hide();
    jQuery('.user-pass2-wrap').hide();

    var virgil = '<tr class="stop-virgil"><th><label for="description">Authentication</label></th><td>You are using Virgil Pass to login to this website. Click <a href="javascript:void(0);" id="show-new-password">here</a> to switch back to using passwords.</td></tr>';
    jQuery('.user-description-wrap').after(virgil);

    jQuery('#show-new-password').click(function() {
        jQuery('#your-profile').append('<input type="hidden" name="stop-using-virgil" value="1" />');
        jQuery('.user-pass1-wrap, .user-pass2-wrap').show();
        jQuery('.stop-virgil').remove();
    })
})