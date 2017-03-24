
var $ = jQuery;

function jump(x){ document.getElementById(x).scrollIntoView(); }

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

jQuery(document).ready(function() {
	
	$(".zespoke-menu-link").hover(function() {
		var testing = $(this).attr("alt-src");
		$(".menu-image").attr("src", testing);

		var subtitle = $(this).attr("menu-sub");
		$(".menu-subtitle").html(subtitle);
	});
	
	$(".zespoke-menu-link").mouseleave(function() {
		$(".menu-subtitle").html("Select a range");
	});
	
	$( "#gform_3 .gform_footer" ).insertAfter( $( "#field_3_8 .ginput_container" ) );
			
	$(".hamburger_btn").click(function() {
			$(".hide-responsive-menu").toggleClass('menuisvisible');
			$(this).find('i').toggleClass( "fa-bars" );
			$(this).find('i').toggleClass( "fa-times menu-active" );
	});
	
	$(".search_btn").click(function() {
			$(".responsive-search").toggleClass('searchvisible');
	});
	
	$(".SlideMenu1_Folder div").click(function() {
			$(this).parent().find('ul').toggle();
			$(this).find('i').toggleClass( "fa-rotate-90" );
			$('.white-line').not(this).each(function(){
				$(this).toggleClass("white-line");
				$(this).parent().find('ul').toggle();
				$(this).parent().find('i').removeClass( "fa-rotate-90" );
			});
			$(this).toggleClass("white-line");
	});
	
});

jQuery('a[href^="#"]').on('click', function(event) {
    var target = $(this.getAttribute('href'));
    if( target.length ) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top + -30
        }, 1000);
    }
});
