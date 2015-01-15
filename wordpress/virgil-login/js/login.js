jQuery(document).ready(function(){
    var loginform = jQuery('#loginform,#front-login-form');
    var virgillogin = jQuery('p.virgil-login');

    loginform.prepend("<h3 class='virgil-or'>or</h3>");
    loginform.prepend(virgillogin);
})