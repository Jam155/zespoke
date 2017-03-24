jQuery(document).ready(function() {

	console.log(jQuery('.owl-carousel'));

	var owls = jQuery('.owl-carousel');

	owls.each(function(index) {

		console.log("Owl");
		var items = jQuery(this).children().length;
		var mobile = jQuery(this).hasClass('mobile-slider');

		console.log(items > 1 && mobile);

		jQuery(this).owlCarousel({

			loop: items > 1,
			nav: items > 1 && !mobile,
			autoplay: true,
			items: 1,

		});

	});

	setTimeout(function() {

		jQuery('.owl-review').owlCarousel({
			autoplay:true,
			autoplayTimeout:8000,
			autoplayHoverPause:false,
			loop:true,
			dots:false,
			items:1
		});

	}, 500);

	jQuery('.brand-carousel').owlCarousel({

        	loop: true,
        	nav: true,
        	stagePadding: 50,
        	mergeFit: true,
		autoplay: true,
        	responsiveClass:true,
        	responsive:{
        	        0:{
        	                items:1,
        	        },
        	        317:{
        	                items:2,
        	        },
        	        600:{
        	                items:3,
        	        },
        	        800:{
        	                items:4,
        	        },
        	        1000:{
        	                items:6,
        	        }
        	}
	});

	jQuery('.product-carousel').owlCarousel({
		loop: true,
		nav: false,
		items: 1,
		autoplay: true,
		autoplayTimeout: 2000,
		autoplayHoverPause: true,

	});

});
