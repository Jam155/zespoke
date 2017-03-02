<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="row">
		<div class="large-12 columns">
			<div class="row collapse">
				<div class="small-11 mobile-three columns">
					<input type="text" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'zespoke' ); ?>" />
				</div>
				<div class="small-1 mobile-one columns">
					<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
			</div>
		</div>
	</div>
</form>