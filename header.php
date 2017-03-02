<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="https://www.zespoke.com/wp-content/themes/zespoke/js/header.js" type="text/javascript" async="async"></script>
	<script src="https://www.zespoke.com/wp-content/themes/zespoke/js/homepage.js" type="text/javascript" async="async"></script>
	<?php wp_localize_script('mylib', 'WPURLS', array( 'siteurl' => get_option('siteurl') )); ?>
	
	<script type="text/javascript">
		var ajaxurl = '<?php echo home_url(); ?>';
	</script>
	
	<?php if( is_page('contact-us') ) { ?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAZn8gW9rN6OLwWVk26fZW3zEwt8r2pe_Q"></script>
        
        <script type="text/javascript">
			var templateUrl = '<?= get_bloginfo("template_url"); ?>';
            google.maps.event.addDomListener(window, 'load', init);
        
            function init() {
                var mapOptions = {
                    zoom: 15,
                    center: new google.maps.LatLng(54.6047787, -6.7304880),
                    styles: [{"featureType":"all","elementType":"all","stylers":[{"saturation":"39"}]},{"featureType":"all","elementType":"geometry.fill","stylers":[{"lightness":"53"},{"saturation":"0"},{"visibility":"simplified"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"lightness":"-37"},{"weight":"0.82"},{"visibility":"simplified"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#35a0ba"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"lightness":"100"},{"weight":"5.25"},{"gamma":"0.30"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"},{"color":"#757575"},{"lightness":"10"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","elementType":"all","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[{"lightness":"31"},{"hue":"#ff0000"}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#00cdff"},{"lightness":"-12"},{"saturation":"-49"}]},{"featureType":"water","elementType":"geometry.stroke","stylers":[{"lightness":"-53"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]}]
                };

                var mapElement = document.getElementById('map');

                var map = new google.maps.Map(mapElement, mapOptions);

				var image = templateUrl + '/imgs/mappin.png';
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(54.6047787, -6.7304880),
                    map: map,
					icon: image,
                    title: 'Zespoke'
                });
            }
        </script>
	<?php } ?>
	
	<script type="text/javascript" src="https://s3-eu-west-1.amazonaws.com/segmentum-js/zespoke.com/segmentum_v2.min.js"></script>
	<?php wp_head(); ?>
</head>

<body <?php body_class('no-zopim'); ?>>

	<header>

		<div class="top">
		
			<div class="row">
		
				<div class="left medium-4 small-3 columns nopaddingright">
			
					<ul>
				
						<li><tel><?php echo get_field('phone', 'options'); ?></tel></li>
						<li><a href="https://v2.zopim.com/widget/popout.html?key=1MLAwhAxT0s2nYHN0SM0xZzRNhbAN1vu" target="_blank" class="help">Live Help</a></li>
					</ul>
			
				</div>
			
				<div class="right medium-8 small-9 columns nopaddingleft">
			
					<ul>
						<li><?php echo do_shortcode('[WoocommerceWooCartPro]'); ?></li>
						<li><?php do_action('kino_language_switcher'); ?></li>
					</ul>
					
				</div>
			
			</div>
		
		</div>
		
		<div class="bottom">
		
			<div class="row">
		
				<div class="medium-10 small-12 columns show-for-large">
						
					<a href="<?php echo get_home_url(); ?>" alt="Zespoke | Don't Be Dull" title="Zespoke | Don't Be Dull"><img class="logo-small" src="<?php echo get_template_directory_uri() . '/imgs/zespoke-logo.png'; ?>" /></a>
		
					<span class="logo-tagline">Don't be dull..</span>
				
					<div class="search">

						<?php echo get_search_form(); ?>
		
					</div>
		
				</div>
				
				<div class="medium-12 small-12 columns show-for-small hide-for-large">
				
				
					<div class="row bottom-bar-responsive">
						<div class="small-4 columns">
							<div class="hamburger_btn"><i class="fa fa-bars" aria-hidden="true"></i> Menu</div>
						</div>
						
						<div class="small-6 columns">
							<a href="<?php echo get_home_url(); ?>" alt="Zespoke | Don't Be Dull" title="Zespoke | Don't Be Dull"><img class="logo-small" src="<?php echo get_template_directory_uri() . '/imgs/zespoke-logo.png'; ?>" /></a>
							<span class="logo-tagline">Don't be dull..</span>
						</div>
					
						<div class="small-2 columns">
							<div class="search_btn"><i class="fa fa-search" aria-hidden="true"></i></div>
						</div>
					</div>
		
				</div>
				
				<div class="search responsive-search">
					<?php echo get_search_form(); ?>
				</div>
		
				<div class="medium-2 right columns nopaddingright show-for-large">
					<a href="<?php echo get_permalink(659); ?>">
						<div class="trusted"></div>
					</a>
				</div>
		
			</div>
		
		</div>
		
		<div id="SlideMenu1 hidden responsive-menu" class="hide-responsive-menu">
			<ul>
				<?php $coffeeTablesURL = get_term_link(7, 'product_cat'); ?>
				<li class="SlideMenu1_Folder"><div>Coffee Tables<i class="fa fa-chevron-right" aria-hidden="true"></i></div>
					<ul class="sub-menu">
						<span>Select a range</span>
						<li><a href="<?php echo $coffeeTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon">Customise It</a></li>
						<li><a href="<?php echo $coffeeTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon">Solo Colour</a></li>
						<li><a href="<?php echo $coffeeTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon">Graphic Range</a></li>
						<li><a href="<?php echo $coffeeTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon">Mix It Up</a></li>
					</ul>
				</li>
				<?php $consoleTablesURL = get_term_link(8, 'product_cat'); ?>
				<li class="SlideMenu1_Folder"><div>Console Tables<i class="fa fa-chevron-right" aria-hidden="true"></i></div>
					<ul class="sub-menu">
						<span>Select a range</span>
						<li><a href="<?php echo $consoleTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon">Customise It</a></li>
						<li><a href="<?php echo $consoleTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon">Solo Colour</a></li>
						<li><a href="<?php echo $consoleTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon">Graphic Range</a></li>
						<li><a href="<?php echo $consoleTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon">Mix It Up</a></li>
					</ul>
				</li>
				<?php $sideTablesURL = get_term_link(6, 'product_cat'); ?>
				<li class="SlideMenu1_Folder"><div>Side Tables<i class="fa fa-chevron-right" aria-hidden="true"></i></div>
					<ul class="sub-menu">
						<span>Select a range</span>
						<li><a href="<?php echo $sideTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon">Customise It</a></li>
						<li><a href="<?php echo $sideTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon">Solo Colour</a></li>
						<li><a href="<?php echo $sideTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon">Graphic Range</a></li>
						<li><a href="<?php echo $sideTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon">Mix It Up</a></li>
					</ul>
				</li>
				<?php $tvStandsURL = get_term_link(9, 'product_cat'); ?>
				<li class="SlideMenu1_Folder"><div>TV Stands<i class="fa fa-chevron-right" aria-hidden="true"></i></div>
					<ul class="sub-menu">
						<span>Select a range</span>
						<li><a href="<?php echo $tvStandsURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon">Customise It</a></li>
						<li><a href="<?php echo $tvStandsURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon">Solo Colour</a></li>
						<li><a href="<?php echo $tvStandsURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon">Graphic Range</a></li>
						<li><a href="<?php echo $tvStandsURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon">Mix It Up</a></li>
					</ul>
				</li>
				<li class="SlideMenu1_Folder"><div><a href="<?php echo get_permalink(109); ?>">Customer Gallery</a></div></li>
				<li class="SlideMenu1_Folder"><div><a href="<?php echo get_permalink(111); ?>">FAQ</a></div></li>
			</ul>
		</div>
					
		<div class="menu show-for-large">		
			<div class="row">
		
				<div class="small-12 columns nopadding ">

					<ul>
						<?php $coffeeTablesURL = get_term_link(7, 'product_cat'); ?>
						<li class="has-submenu <?php if (is_product_category('coffee-tables')) { echo 'active'; } ?>"><a href="<?php echo $coffeeTablesURL; ?>">Coffee Tables</a>
							<div class="menu-outside">
								<div class="row menu-inside">
									<?php
									$rows = get_field('coffee_tables', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it1 = $row['customise_it'];
											$solo_colour1 = $row['solo_colour'];
											$graphic_range1 = $row['graphic_range'];
											$mix_it_up1 = $row['mix_it_up'];
										}
									} ?>
									<?php
									$rows = get_field('coffee_tables_images', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it_img = $row['customise_it'];
											if( empty($customise_it_img) ) { $customise_it_img = get_template_directory_uri().'/imgs/menu/customise-it-menu.png'; }
											
											$solo_colour_img = $row['solo_colour'];
											if( empty($solo_colour_img) ) { $solo_colour_img = get_template_directory_uri().'/imgs/menu/solid-colour-menu.png'; }
											
											$graphic_range_img = $row['graphic_range'];
											if( empty($graphic_range_img) ) { $graphic_range_img = get_template_directory_uri().'/imgs/menu/graphic-menu.png'; }
											
											$mix_it_up_img = $row['mix_it_up'];
											if( empty($mix_it_up_img) ) { $mix_it_up_img = get_template_directory_uri().'/imgs/menu/mix-n-match-menu.png'; }
										} 
									} ?>
									<div class="small-2 columns nopadding"><a href="<?php echo $coffeeTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon" alt-src="<?php echo $customise_it_img; ?>" menu-sub="<?php echo $customise_it1; ?>">Customise It</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $coffeeTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon" alt-src="<?php echo $solo_colour_img; ?>" menu-sub="<?php echo $solo_colour1; ?>">Solo Colour</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $coffeeTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon" alt-src="<?php echo $graphic_range_img; ?>" menu-sub="<?php echo $graphic_range1; ?>">Graphic Range</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $coffeeTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon" alt-src="<?php echo $mix_it_up_img; ?>" menu-sub="<?php echo $mix_it_up1; ?>">Mix It Up</a></div>
									<div class="small-4 columns nopadding"><img class="menu-image" src="<?php echo $customise_it_img; ?>" /></div>
									<p class="menu-subtitle">Select a range</p>
								</div>
							</div>
						</li>
						<?php $consoleTablesURL = get_term_link(8, 'product_cat'); ?>
						<li class="has-submenu <?php if (is_product_category('console-tables')) { echo 'active'; } ?>"><a href="<?php echo $consoleTablesURL; ?>">Console Tables</a>
							<div class="menu-outside">
								<div class="row menu-inside">
									<?php
									$rows = get_field('console_tables', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it = $row['customise_it'];
											$solo_colour = $row['solo_colour'];
											$graphic_range = $row['graphic_range'];
											$mix_it_up = $row['mix_it_up'];
										} 
									} ?>
									<?php
									$rows = get_field('console_tables_images', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it_img = $row['customise_it'];
											if( empty($customise_it_img) ) { $customise_it_img = get_template_directory_uri().'/imgs/menu/customise-it-menu.png'; }
											
											$solo_colour_img = $row['solo_colour'];
											if( empty($solo_colour_img) ) { $solo_colour_img = get_template_directory_uri().'/imgs/menu/solid-colour-menu.png'; }
											
											$graphic_range_img = $row['graphic_range'];
											if( empty($graphic_range_img) ) { $graphic_range_img = get_template_directory_uri().'/imgs/menu/graphic-menu.png'; }
											
											$mix_it_up_img = $row['mix_it_up'];
											if( empty($mix_it_up_img) ) { $mix_it_up_img = get_template_directory_uri().'/imgs/menu/mix-n-match-menu.png'; }
										} 
									} ?>
									<div class="small-2 columns nopadding"><a href="<?php echo $consoleTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon" alt-src="<?php echo $customise_it_img; ?>" menu-sub="<?php echo $customise_it; ?>">Customise It</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $consoleTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon" alt-src="<?php echo $solo_colour_img; ?>" menu-sub="<?php echo $solo_colour; ?>">Solo Colour</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $consoleTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon" alt-src="<?php echo $graphic_range_img; ?>" menu-sub="<?php echo $graphic_range; ?>">Graphic Range</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $consoleTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon" alt-src="<?php echo $mix_it_up_img; ?>" menu-sub="<?php echo $mix_it_up; ?>">Mix It Up</a></div>
									<div class="small-4 columns nopadding"><img class="menu-image" src="<?php echo $customise_it_img; ?>" /></div>
									<p class="menu-subtitle">Select a range</p>
								</div>
							</div>
						</li>
						<?php $sideTablesURL = get_term_link(6, 'product_cat'); ?>
						<li class="has-submenu <?php if (is_product_category('side-tables')) { echo 'active'; } ?>"><a href="<?php echo $sideTablesURL; ?>?filter=customise-it">Side Tables</a>
							<div class="menu-outside">
								<div class="row menu-inside">
									<?php
									$rows = get_field('side_tables', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it = $row['customise_it'];
											$solo_colour = $row['solo_colour'];
											$graphic_range = $row['graphic_range'];
											$mix_it_up = $row['mix_it_up'];
										} 
									} ?>
									<?php
									$rows = get_field('side_tables_images', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it_img = $row['customise_it'];
											if( empty($customise_it_img) ) { $customise_it_img = get_template_directory_uri().'/imgs/menu/customise-it-menu.png'; }
											
											$solo_colour_img = $row['solo_colour'];
											if( empty($solo_colour_img) ) { $solo_colour_img = get_template_directory_uri().'/imgs/menu/solid-colour-menu.png'; }
											
											$graphic_range_img = $row['graphic_range'];
											if( empty($graphic_range_img) ) { $graphic_range_img = get_template_directory_uri().'/imgs/menu/graphic-menu.png'; }
											
											$mix_it_up_img = $row['mix_it_up'];
											if( empty($mix_it_up_img) ) { $mix_it_up_img = get_template_directory_uri().'/imgs/menu/mix-n-match-menu.png'; }
										} 
									} ?>
									<div class="small-2 columns nopadding"><a href="<?php echo $sideTablesURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon" alt-src="<?php echo $customise_it_img; ?>" menu-sub="<?php echo $customise_it; ?>">Customise It</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $sideTablesURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon" alt-src="<?php echo $solo_colour_img; ?>" menu-sub="<?php echo $solo_colour; ?>">Solo Colour</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $sideTablesURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon" alt-src="<?php echo $graphic_range_img; ?>" menu-sub="<?php echo $graphic_range; ?>">Graphic Range</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $sideTablesURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon" alt-src="<?php echo $mix_it_up_img; ?>" menu-sub="<?php echo $mix_it_up; ?>">Mix It Up</a></div>
									<div class="small-4 columns nopadding"><img class="menu-image" src="<?php echo $customise_it_img; ?>" /></div>
									<p class="menu-subtitle">Select a range</p>
								</div>
							</div>
						</li>
						<?php $tvStandsURL = get_term_link(9, 'product_cat'); ?>
						<li class="has-submenu <?php if (is_product_category('tv-stands')) { echo 'active'; } ?>"><a href="<?php echo $tvStandsURL; ?>?filter=customise-it">TV Stands</a>
							<div class="menu-outside">
								<div class="row menu-inside">
									<?php
									$rows = get_field('tv_stands', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it = $row['customise_it'];
											$solo_colour = $row['solo_colour'];
											$graphic_range = $row['graphic_range'];
											$mix_it_up = $row['mix_it_up'];
										} 
									} ?>
									<?php
									$rows = get_field('tv_stands_images', 665);
									if($rows) {
										foreach($rows as $row) {
											$customise_it_img = $row['customise_it'];
											if( empty($customise_it_img) ) { $customise_it_img = get_template_directory_uri().'/imgs/menu/customise-it-menu.png'; }
											
											$solo_colour_img = $row['solo_colour'];
											if( empty($solo_colour_img) ) { $solo_colour_img = get_template_directory_uri().'/imgs/menu/solid-colour-menu.png'; }
											
											$graphic_range_img = $row['graphic_range'];
											if( empty($graphic_range_img) ) { $graphic_range_img = get_template_directory_uri().'/imgs/menu/graphic-menu.png'; }
											
											$mix_it_up_img = $row['mix_it_up'];
											if( empty($mix_it_up_img) ) { $mix_it_up_img = get_template_directory_uri().'/imgs/menu/mix-n-match-menu.png'; }
										} 
									} ?>
									<div class="small-2 columns nopadding"><a href="<?php echo $tvStandsURL; ?>?filter=customise-it" class="zespoke-menu-link customise-icon" alt-src="<?php echo $customise_it_img; ?>" menu-sub="<?php echo $customise_it; ?>">Customise It</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $tvStandsURL; ?>?filter=solo-colour" class="zespoke-menu-link solid-icon" alt-src="<?php echo $solo_colour_img; ?>" menu-sub="<?php echo $solo_colour; ?>">Solo Colour</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $tvStandsURL; ?>?filter=graphic" class="zespoke-menu-link graphic-icon" alt-src="<?php echo $graphic_range_img; ?>" menu-sub="<?php echo $graphic_range; ?>">Graphic Range</a></div>
									<div class="small-2 columns nopadding"><a href="<?php echo $tvStandsURL; ?>?filter=mix-it-up" class="zespoke-menu-link mix-icon" alt-src="<?php echo $mix_it_up_img; ?>" menu-sub="<?php echo $mix_it_up; ?>">Mix It Up</a></div>
									<div class="small-4 columns nopadding"><img class="menu-image" src="<?php echo $customise_it_img; ?>" /></div>
									<p class="menu-subtitle">Select a range</p>
								</div>
							</div>
						</li>
						<li class="<?php if (is_page('customer-gallery') ) { echo 'active'; } ?>"><a href="<?php echo get_permalink(109); ?>">Customer Gallery</a></li>
						<li class="<?php if (is_page('faq') ) { echo 'active'; } ?>"><a href="<?php echo get_permalink(111); ?>">FAQ</a></li>
					</ul>

				</div>
	
			</div>	
		</div>
		<div class="show-for-large wide-menu-under-border"></div>

		<?php $salespoints = get_field('sales_points', 'options'); ?>

		<?php if (isset($salespoints)): ?>

		<div class="salespoints row medium-unstack">

			<div class="small-12 columns">
			
				<h2><?php echo $salespoints[0]['usb']; ?></h2>
			
			</div>
			
			<div class="small-12 columns">
			
				<h2><?php echo $salespoints[1]['usb']; ?></h2>
			
			</div>
			
			<div class="small-12 columns">
			
				<h2><?php echo $salespoints[2]['usb']; ?></h2>
			
			</div>

		</div>
		
		<?php endif; ?>
	
<?php if( !(is_front_page() || is_product()) ) { ?>
	<div class="row breadcrumbs">
		<div class="small-12 columns">
			<?php if ( function_exists('yoast_breadcrumb') ) {	yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>
		</div>
	</div>
<?php } ?>

<?php if ( !is_checkout() && !is_front_page() && is_page() ) : ?>
<header class="entry-header row">
	<h1 class="small-12 entry-title"><?php the_title(); ?></h1>
	<?php if(is_page('customer-gallery')) { ?><p class="page-subtitle">See our customised Zespoke tables in action</p> <?php } ?>
</header>
<?php endif; ?>

<?php if(isset($_GET['filter'])) { ?>

<!-- There must be a better way of doing this menu - at the moment it's kind of clunky -->
<?php $cat_string = str_replace(' ', '-', strtolower(single_cat_title('', false))); ?>
<div class="row show-for-large">
	<div class="small-12 columns nopadding ">
		<div class="menu-for-cats">
			<div class="row menu-inside">
				<div class="small-2 columns nopadding<?php if(htmlspecialchars($_GET["filter"]) == 'customise-it') { echo " isactive"; } ?>"><a href="?filter=customise-it" class="zespoke-menu-link customise-icon" >Customise It</a></div>
				<div class="small-2 columns nopadding<?php if(htmlspecialchars($_GET["filter"]) == 'solo-colour') { echo " isactive"; } ?>"><a href="?filter=solo-colour" class="zespoke-menu-link solid-icon" >Solo Colour</a></div>
				<div class="small-2 columns nopadding<?php if(htmlspecialchars($_GET["filter"]) == 'graphic') { echo " isactive"; } ?>"><a href="?filter=graphic" class="zespoke-menu-link graphic-icon" >Graphic Range</a></div>
				<div class="small-2 columns nopadding<?php if(htmlspecialchars($_GET["filter"]) == 'mix-it-up') { echo " isactive"; } ?>"><a href="?filter=mix-it-up" class="zespoke-menu-link mix-icon" >Mix It Up</a></div>
			
				<?php

					/** Really needs redo-ing **/

					$rows = get_field('tv_stands_images', 665);

					if ($rows) {

						foreach($rows as $row) {

							$customise_img = $row['customise_it'];
							$solo_img = $row['solo_colour'];
							$graphic_img = $row['graphic_range'];
							$mix_img = $row['mix_it_up'];

						}

					}

				?>

				<?php if (htmlspecialchars($_GET["filter"]) == 'customise-it') { $getCatImage = $customise_img; } ?>
				<?php if (htmlspecialchars($_GET["filter"]) == 'solo-colour') { $getCatImage = $solo_img; } ?>
				<?php if (htmlspecialchars($_GET["filter"]) == 'graphic') { $getCatImage = $graphic_img; } ?>
				<?php if (htmlspecialchars($_GET["filter"]) == 'mix-it-up') { $getCatImage = $mix_img; } ?>
				
				<div class="small-4 columns nopadding"><img class="menu-image-head" src="<?php echo $getCatImage; ?>" /></div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php if( !is_product() && !is_cart() && !is_checkout() && !is_home() && !is_single() && !is_page( array( 'customer-gallery', 'contact-us', 'delivery-information', 'handmade-in-the-uk', 'faq', 'about-zespoke', 'product-care', 'terms-and-conditions', 'request-free-sample-pack' ) ) ) { ?>

<div class="imageslider row">

	<div class="small-12 columns nopadding">
	
		<div class="owl-carousel main-slider">
		
			<?php

				$filter = null;

				if (isset($_GET['filter'])) {

                                        $filter = htmlspecialchars($_GET["filter"]);

                                }

                                $images = kino_get_header_slider_images($filter);

			?>

			
			<?php foreach($images as $image): ?>
				<?php
					$hasurl = $image["banner_url"];
					if ( !empty($hasurl) ) { echo '<a href="'. $image["banner_url"] .'">'; }
				?>
				<div class="item"><img src="<?php echo $image["image"]; ?>" /></div>
				
				<?php if ( !empty($hasurl) ) { echo '</a>'; } ?>
			
			<?php endforeach; ?>
		
		</div>

		<div class="owl-carousel mobile-slider">

			<?php $images = get_field('mobile_header_slider', 169); ?>

			<?php

				$filter = null;

				if (isset($_GET['filter'])) {

					$filter = htmlspecialchars($_GET["filter"]);

				}

				$images = kino_get_header_mobile_slider_images($filter);

			?>

			<?php foreach ($images as $image): ?>

				<?php
					$hasurl = $image["mobile_banner_url"];
					if ( !empty($hasurl) ) { echo '<a href="'. $image["mobile_banner_url"] .'">'; }
				?>
				<div class="item"><img src="<?php echo $image["image"]; ?>" /></div>
				
				<?php if ( !empty($hasurl) ) { echo '</a>'; } ?>
				
			<?php endforeach; ?>

		</div>
	
	</div>

</div>

<?php wp_reset_query(); ?>
<?php } ?>

<?php if( is_front_page() ) { ?>
	<div class="row home-free-sample">
		<div class="small-12 columns">
			<h2><a href="<?php echo get_permalink(713); ?>">Request a <span class="fancy">Free</span> sample pack now!</a></h2>
		</div>
	</div>
<?php } ?>
	
	</header>
