var $ = require('jquery');

$(function(){
    $('.js-pagetop-btn').on('click', function(){
        $('html, body').animate({scrollTop: 0});
        return false;
    });
});