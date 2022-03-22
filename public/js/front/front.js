$(document).ready(function(){
	// Smoothscroll
    $('.smooth').on('click',function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
		     'scrollTop': $target.offset().top
		}, 900, 'swing');
    });

    // Back to tiop button
    var amountScrolled = 300;
	$(window).scroll(function() {
	    if ( $(window).scrollTop() > amountScrolled ) {
	        $('a.to-top').fadeIn('slow');
	    } else {
	        $('a.to-top').fadeOut('slow');
	    }
	});

	$('a.to-top').click(function() {
	    $('html, body').animate({
	        scrollTop: 0
	    }, 700);
	    return false;
	});
});