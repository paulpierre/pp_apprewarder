/***********************!
 Navbar resize on scroll
 ************************/
 if( $(window).width() > 979 ) {

$(document).on("scroll",function(){
$("#wrapheader").toggleClass("small", $(document).scrollTop()>70);
});
}
/***********************!
 Bootstrap tooltip
 ************************/

    $(function() {
    $('.post a,#forum-menu a.btn-navbar,.soc-content ul li a').tooltip({placement: 'bottom'});
    $('.status-position i').tooltip({placement: 'right'});

});

/***********************!
 Back to top button
 ************************/

$(document).ready(function() {
	// Show or hide the sticky footer button
	 $(window).scroll(function() {
		if ($(this).scrollTop() > 200) {
				$('.go-top').fadeIn(200);
			} else {
					$('.go-top').fadeOut(200);
			}
		});
			
		// Animate the scroll to top
		$('.go-top').click(function(event) {
			event.preventDefault();
				
			$('html, body').animate({scrollTop: 0}, 300);
		})
});
	
/***********************!
 Collapse menu transition
 ************************/

// Bind event to every .btn-navbar button
 $('.btn-navbar').click(function(){
     // Select the .nav-collapse within the same .navbar as the current button
     var nav = $(this).closest('.navbar').find('.nav-collapse');
     // If it has a height, hide it
     if (nav.height() != 0) {
         nav.height(0);
     // If it's collapsed, show it
     } else {
         nav.height('auto');
     }
 });
 
/***********************!
 Collapse forum menu 
 ************************/
var c = document.cookie;

$('#collapse-menu.collapse').each(function () {
    if (this.id) {
        var pos = c.indexOf(this.id + "_collapse_in=");
        if (pos > -1) {
            c.substr(pos).split('=')[1].indexOf('false') ? $(this).addClass('in') : $(this).removeClass('in');
        }
    }
}).on('hidden shown', function () {
    if (this.id) {
        document.cookie = this.id + "_collapse_in=" + $(this).hasClass('in');
    }
});