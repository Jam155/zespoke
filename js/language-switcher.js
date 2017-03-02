
jQuery(document).ready(function() {

	jQuery('.kino-language-switcher').on('click', function(e) {

		jQuery(this).toggleClass('active');
		jQuery(this).find('.other-languages').toggleClass('hidden');
		jQuery(this).find('.open-closed').toggleClass('fa-angle-down').toggleClass('fa-angle-up');

	});

});
