$(document).ready(function() {

//============ Navmenu ============//

$('.top-nav li').localScroll();

$('.top-nav').mobileMenu({
	defaultText: 'Navigation',
	className: 'select-menu',
	subMenuDash: '&ndash;'
});

//============ Flexslider ============//

$('#main-slider').flexslider({
    animation: "fade",
    slideshowSpeed: 3500
});

$('.flexslider').flexslider({
    animation: "slide",
    controlNav: "thumbnails"
});

$('.top-nav').onePageNav();

//============ Fixed header ============//

$(window).scroll( function() {
    var value = $(this).scrollTop();
    if ( value > 350 )
        $(".top-nav li").css("padding", "20px 15px 0px");
    else
        $(".top-nav li").css("padding", "30px 15px 10px");
});

$(window).scroll( function() {
    var value = $(this).scrollTop();
    if ( value > 350 )
        $(".logo h1").css("margin", "0px 0 0 0");
    else
        $(".logo h1").css("margin", "10px 0 0 0");
});


});

