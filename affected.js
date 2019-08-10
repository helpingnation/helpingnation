$(document).ready(function() {
    if(window.screen.width < 600) {
        $('#register').addClass('form-control');
    }
    else {
        $('#register').removeClass('form-control');
    }
})