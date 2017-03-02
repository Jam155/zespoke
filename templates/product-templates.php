<?php /** Javascript Template for an Individual Product **/ ?>
<script type="text/html" id="tmpl-product-single">

	<div class="small-6 medium-3">
		<a href="{{data.url}}" "title="{{data.title}}" alt="{{data.title}}" class='popular-designs'>

			<div class="image">

				<# if (data.customise) { #>

					<span>Customise</span>
					<img class="customise_logo" src="<?php echo get_template_directory_uri(); ?>/imgs/customise-logo.png" />

				<# } #>

				{{{data.image}}}

			</div>

			<h2>{{{data.title}}}</h2>

			<p class="price">

				<span class="rrp">RRP: {{{data.rrp}}}</span><span class="saving">Save {{data.saving}}%</span><span class="sale">{{{data.sale}}}</span>

			</p>

		</a>

	</div>

</script>
