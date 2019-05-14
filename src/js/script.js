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

    $(document).on('click', '.js-thumb-remove', function(e){
        $(this).siblings('label').find('img').attr('src', '');
    });

    $(document).on('click touchend', '.js-modal-open', function(e){
        e.preventDefault();
        $('.js-modal').addClass('is-show');
    });
    $(document).on('click', '.js-modal-close', function(e){
        e.preventDefault();
        $('.js-modal').removeClass('is-show');
    });

    $('.js-cat-add').on('click', function(){
        var catname = $('.js-cat-name').val();
        if(catname !== ''){
            $.ajax({
                url: 'addCat.php',
                type: 'POST',
                data: { catname: catname },
            }).done(function(data){
                var options = document.getElementsByTagName('option');

                $('.js-select-cat').append($('<option>').val($('option').length).text(catname));
                $('.js-cat-name').val('');
                $('.js-modal').removeClass('is-show');
            }).fail(function(){
                console.warn('ajax error');
            });
        }
    });
});