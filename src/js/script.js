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

    // 画像投稿
    $('.js-post-image')
        .on('dragover', function(){
            $(this).parent('label').addClass('is-dragover');
        })
        .on('dragleave', function(){
            $(this).parent('label').removeClass('is-dragover');
        })
        .on('change', function(){
            $(this).parent('label').removeClass('is-dragover');
            var file = this.files[0];
            var $image = $(this).siblings('img');
            var fileReader = new FileReader();

            fileReader.onload = function(e){
                $image.attr('src', e.target.result).show();
            };
            fileReader.readAsDataURL(file);
        });
});