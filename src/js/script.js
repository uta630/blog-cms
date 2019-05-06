var jQuery = require('jquery');
var $ = jQuery;
window.jQuery = jQuery;
require('../../node_modules/bxslider/dist/jquery.bxslider.min.js');

$(function(){
    $('.js-pagetop-btn').on('click', function(){
        $('html, body').animate({scrollTop: 0});
        return false;
    });
    
    $('.js-bxslider').bxSlider({
        infiniteLoop: false,
        preloadImages: 'visible',
        prevSelector: '.c-post__icon--prev',
        prevText: '',
        nextSelector: '.c-post__icon--next',
        nextText: '',
    });
});