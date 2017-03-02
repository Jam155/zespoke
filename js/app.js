(function($) {

	jQuery(document).ready(function(e) {
	
		$(document).foundation();

	});

	$(document).ready(function(e) {

		function salespoints() {

			var isSmall = !Foundation.MediaQuery.atLeast('medium');

			if (isSmall) {

				jQuery('.salespoints').addClass('small');

			} else {

				jQuery('.salespoints').removeClass('small');

			}

		}

		function topnav() {

			var isSmall = !Foundation.MediaQuery.atLeast('medium');

			if (isSmall) {

				jQuery('header div.top div.right ul').addClass('responsive-cart');

			} else {

				jQuery('header div.top div.right ul').removeClass('responsive-cart');

			}

		}

		function isSmall() {

			return !Foundation.MediaQuery.atLeast('medium');

		}

		function livehelp() {

			$('a.help').on('click', function(e) {

				small = isSmall();

				if (!small) {

					e.preventDefault();
					jQuery('body').toggleClass('no-zopim');

				}

			})

		}

		salespoints();
		topnav();
		livehelp();

		$(window).resize(function(e) {

			salespoints();
			topnav();

		});

	});

})(jQuery);
