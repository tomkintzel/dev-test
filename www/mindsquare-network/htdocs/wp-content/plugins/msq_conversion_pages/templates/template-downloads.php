<?php

// Speichere die Blog-Informationen ab
$current_blog_id = get_current_blog_id();

// Lade alle Informationen vom Download
$post_title = get_the_title();
$conversion_text = get_field( 'download_text' );
$conversion_bulletlist = get_field( 'download_bulletpoints' );
$conversion_img = get_field( 'download_image' );
$conversion_content = get_field( 'download_content' );
$conversion_title = get_field( 'download_form_title' ) ?: 'Herunterladen';
$conversion_pardot_id = get_field( 'download_form_pardot' );

// Lade weitere statische Informationen
$current_plugin_dir = dirname( __FILE__ );
$post_background = 'hintergrundbild.jpg';
$post_background_path = plugin_dir_path( $current_plugin_dir ) . '/assets/img/' . $post_background;
$post_background_url = plugins_url( 'assets/img/' . $post_background, $current_plugin_dir );
$post_sap = 'sap-partner.png';
$post_sap_alt = 'SAP Partner';
$post_sap_path = plugin_dir_path( $current_plugin_dir ) . '/assets/img/' . $post_sap;
$post_sap_url = plugins_url( 'assets/img/' . $post_sap, $current_plugin_dir );
$post_referenzen = 'referenzen.png';
$post_referenzen_alt = 'Referenzen';
$post_referenzen_path = plugin_dir_path( $current_plugin_dir ) . '/assets/img/' . $post_referenzen;
$post_referenzen_url = plugins_url( 'assets/img/' . $post_referenzen, $current_plugin_dir );

// Lade das Pardot-Formular
restore_current_blog();
$conversion_pardot = msq_get_pardot_form( array(
	'form_id' => $conversion_pardot_id,
	'height' => '300px',
	'querystring' => htmlentities( $_SERVER[ 'QUERY_STRING' ], ENT_QUOTES )
));

// Erstelle den Header
// Hier wird noch Bootstrap v3 verwendet
require( plugin_dir_path(__FILE__) . 'header.php' );
$image_thumb = wp_get_attachment_image_src( attachment_url_to_postid( get_theme_mod( 'fb_logo' ) ), 'msq_conversion_pages_logo' );
$image_thumb_alt = esc_attr( get_bloginfo( 'name', 'display' ) );
switch_to_blog( $current_blog_id ); ?>
<div id="cv-wrapper" class="template-downloads">
	<div class="container">
		<div class="row main-row">
			<?php if( !empty( $image_thumb ) ): ?>
				<div class="col-xs-12 visible-xs-block">
					<div class="logo">
						<img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-xs-12 visible-xs-block">
				<h1><?php echo $post_title; ?></h1>
			</div>
			<?php if( !empty( $conversion_pardot ) ): ?>
				<div class="col-xs-12 col-sm-6 col-lg-4 col-sm-push-6 col-lg-push-8 pardot-col">
					<div class="pardot-affix">
						<?php if( file_exists( $post_sap_path ) ): ?>
							<div class="img-wrapper">
								<div class="sap-img">
									<img src="<?php echo $post_sap_url; ?>" alt="<?php echo $post_sap_alt; ?>" />
								</div>
							</div>
						<?php endif; ?>
						<?php if( !empty( $conversion_title ) ): ?>
							<strong><?php echo $conversion_title; ?></strong>
						<?php endif; ?>
						<div id="pardot-form">
							<div class="form">
								<?php echo $conversion_pardot; ?>
							</div>
						</div>
						<?php if( file_exists( $post_referenzen_path ) ): ?>
							<div class="img-wrapper referenzen-img">
								<img src="<?php echo $post_referenzen_url; ?>" alt="<?php echo $post_referenzen_alt; ?>" />
							</div>
						<?php endif; ?>
					</div>
					<?php if( file_exists( $post_background_path ) ): ?>
						<div class="bg-img-wrapper">
							<div style="background-image: url(<?php echo $post_background_url; ?>)"></div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="col-xs-12 col-sm-6 col-lg-8 col-sm-pull-6 col-lg-pull-4 main-col">
				<?php if( !empty( $image_thumb ) ): ?>
					<div class="hidden-xs">
						<div class="logo">
							<img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/>
						</div>
					</div>
				<?php endif; ?>
				<div class="hidden-xs">
						<h1><?php echo $post_title; ?></h1>
				</div>
				<?php if ( !empty( $conversion_content ) ) : ?>
					<?php if( !empty( $conversion_text ) ): ?>
						<div class="introduction">
							<?php echo $conversion_text; ?>
						</div>
					<?php endif; ?>
					<?php if( !empty( $conversion_bulletlist ) ): ?>
						<div class="bulletpoints">
							<ul>
								<?php foreach( $conversion_bulletlist as $bulletpoint ): ?>
									<li><?php echo $bulletpoint[ 'bullet_text' ]; ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<div class="description">
						<?php if( !empty( $conversion_img ) ): ?>
							<div class="conversion-img">
								<img src="<?php echo $conversion_img[ 'url' ]; ?>" alt="<?php echo $conversion_img[ 'alt' ]; ?>" />
							</div>
						<?php endif; ?>
						<?php echo $conversion_content; ?>
					</div>
				<?php elseif( !empty( $conversion_img ) || !empty( $conversion_text ) || !empty( $conversion_bulletlist ) ) : ?>
					<div class="description no-main-content">
						<div class="row">
							<?php if ( !empty( $conversion_text ) || !empty( $conversion_bulletlist ) ) : ?>
								<div class="col-xs-12 col-md-8 col-md-push-4">
									<?php if( !empty( $conversion_text ) ): ?>
										<?php echo $conversion_text; ?>
									<?php endif; ?>
									<?php if( !empty( $conversion_bulletlist ) ): ?>
										<ul>
											<?php foreach( $conversion_bulletlist as $bulletpoint ): ?>
												<li><?php echo $bulletpoint[ 'bullet_text' ]; ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if( !empty( $conversion_img ) ): ?>
								<div class="col-xs-12 col-md-4 col-md-pull-8 conversion-img">
									<img src="<?php echo $conversion_img[ 'url' ]; ?>" alt="<?php echo $conversion_img[ 'alt' ]; ?>" />
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-offset-1 col-md-10 footer">
				<p>
					<span><a href="//mindsquare.de/agb/" target="_blank" rel="noopener">AGB</a></span> |
					<span><a href="/impressum/" target="_blank" rel="noopener">Impressum</a></span> |
					<span><a href="/kontakt/" target="_blank" rel="noopener">Kontakt</a></span> |
					<span>Copyright <?php echo date('Y'); ?></span>
				</p>
			</div>
		</div>
	</div>
</div>
<script>
jQuery( function( $ ) {
	var setAffixPosition = function() {
		$( '.pardot-affix' ).css( 'top', Math.min( $( window ).height() - $( '.pardot-affix' ).height(), 0 ) );
	}

	// Die Funktion 'setAffixPosition' ausführen
	window.addEventListener('message', function(event) {
		if((event.origin.match(/^https?:\/\/((staging\d*\.|www2\.)?(mindsquare|innotalent|blog\.mindsquare|mindsquare|maint\-care|mind\-force|mind\-forms|erlebe\-software|activate\-hr|mission\-mobile|rz10|compamind|mind\-logistik|freelancercheck|customer-first-cloud)\.de|go\.pardot\.com)$/i) || event.origin == window.location.origin) && !isNaN(event.data)) {
			setAffixPosition();
		}
	});
	$( window ).on( 'resize', function() {
		setAffixPosition();
	});
	setAffixPosition();
});
</script>
<?php
// Erstelle den Footer
restore_current_blog();
require( plugin_dir_path( __FILE__ ) . 'footer.php' );
switch_to_blog( $current_blog_id );
?>
