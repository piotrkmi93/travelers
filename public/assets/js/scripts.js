/**
 * Created by Piotr on 04.09.2016.
 */
(function(){
    'use strict';

    jQuery.expr.filters.offscreen = function(el) {
        return (
            (el.offsetLeft + el.offsetWidth) < 0
            || (el.offsetTop + el.offsetHeight) < 0
            || (el.offsetLeft > window.innerWidth || el.offsetTop > window.innerHeight)
        );
    };

    $(document).scroll(function(e){
        if($(".panel-user").length){
            if ($(".panel-user").offset().top + $(".panel-user").height() - $(window).scrollTop() < 20){
                $(".user-sidebar").css('position', 'fixed').css('top', '72px');
            } else {
                $(".user-sidebar").css('position', 'absolute').css('top', 'auto');
            }
        }
    });

})();