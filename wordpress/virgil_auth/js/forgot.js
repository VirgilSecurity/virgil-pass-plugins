jQuery(document).ready(function(){
    var lostpasswordform = jQuery('#lostpasswordform');
    var virgillogin = jQuery('p.virgil-login');
    var virgilmessage = jQuery('p.virgil-message');
        virgilmessage.remove();

    lostpasswordform.prepend("<h3 class='virgil-or'>or</h3>");
    lostpasswordform.prepend(virgillogin);
    virgilmessage.insertAfter('p.message');

    jQuery('#lostpasswordform').append('<input type="hidden" name="without_virgil" id="without_virgil" value="1" />')
});