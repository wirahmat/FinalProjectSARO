var selector = 'menu-btn';

$(document).ready(function(){
    $(selector).click(function(){
        $(selector).removeClass("active");
        $(this).addClass("active");
    });
});