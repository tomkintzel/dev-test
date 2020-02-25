<?php
/**
 * Template Name: Erlebe Software - Test
 */

// ACF Felder laden
$image = get_field( 'image' );
$pardot_title = get_field( 'pardot_titel' );
$pardot_form_id = get_field( 'pardot' );

// Blog-Informationen
$currentBlogId = get_current_blog_id();
switch_to_blog( 37 );
restore_current_blog();

// Pardot-Formular erstellen
$queryArray = array();
parse_str( $_SERVER[ 'QUERY_STRING' ], $queryArray );
/**
 * Für RZ10 soll die Schrift nicht weiß werden.
 * @todo Sollte später entfernt werden
 */
if( $currentBlogId != 28 ) {
	$queryArray = [
		'classes'	=> 'Form-whiteText',
		'eventCategory' => 'conversionpage',
		'eventLabel' => get_the_title()
	];
} else {
	$webinarOptionsPartnerImg = get_field( 'webinare_option_partner_logo', 'options' );
}

$pardot = msq_get_pardot_form( array(
	'form_id' => $pardot_form_id,
	'height' => '300px',
	'querystring' => http_build_query( $queryArray )
));
$bulletpoints_beschreibung = get_field( 'bulletpoints_beschreibung' );
$beschreibung = get_field( 'beschreibung' );
if( $trusted_icons = get_field( 'trusted_icons' ) ?: get_field( 'trusted_icons', 'option' ) ) {
	$trusted_icons = array_filter( $trusted_icons, function( $icon ) {
		return !empty( $icon[ 'icon' ] );
	});
}
$formular_position = !empty( get_field( 'formular_position' )[ 0 ] ) ?: false;

$image_thumb = wp_get_attachment_image_src( attachment_url_to_postid( get_theme_mod( 'fb_logo' ) ), 'msq_conversion_pages_logo' );
$image_thumb_alt = esc_attr( get_bloginfo( 'name', 'display' ) );

if( wp_style_is( 'fb-bootstrap4', 'registered' ) ) {
	wp_enqueue_style( 'fb-bootstrap4' );
}

require( plugin_dir_path(__FILE__) . 'header.php' ); ?>
<div id="cv-wrapper" class="template-erlsoft-test theme">
	<div class="cv-header">
		<div class="container">
			<div class="row main-row">
				<?php if( !$formular_position ): ?>
					<div class="col-12 d-md-none">
						<div class="row header-row">
							<div class="col-12 vbottom">
								<?php if( !empty( $image_thumb ) ): ?>
									<div id="logo"><img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/></div>
								<?php endif; ?>
							</div>
							<div class="col-12 vbottom">
								<h1><?php the_title(); ?></h1>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="col-12 col-md-5 col-lg-4 pardot-col order-md-2<?php if( $formular_position )echo ' order-2'; ?>">
					<div class="pardot-affix">
						<div class="bg-color-secondary" id="pardot-form">
							<?php if( !empty( $webinarOptionsPartnerImg[ 'url' ] ) ): ?>
								<div class="Conversionpage-PartnerLogo">
									<img src="<?php echo $webinarOptionsPartnerImg[ 'url' ]; ?>" alt="<?php echo $webinarOptionsPartnerImg[ 'alt' ]; ?>" />
								</div>
							<?php endif; ?>
							<?php if( !empty( $pardot_title ) ): ?>
								<div class="vbottom">
									<h2><?php echo $pardot_title; ?></h2>
								</div>
							<?php endif; ?>
							<?php echo $pardot; ?>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-7 col-lg-8 main-col order-md-1 mt-3 mt-lg-0<?php if( $formular_position )echo ' order-1'; ?>">
					<div class="Conversionpage-Background">
					<div class="row header-row<?php if( !$formular_position )echo ' d-none d-md-flex'; ?>">
						<div class="col-12 col-lg-4 vbottom">
							<?php if( !empty( $image_thumb ) ): ?>
								<div id="logo"><img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/></div>
							<?php endif; ?>
						</div>
						<div class="col-12 col-lg-8 vbottom">
							<h1><?php the_title(); ?></h1>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-lg-4 text-center">
							<div class="main-image">
								<img class="centermedium" src="<?php echo $image[ 'sizes' ][ 'medium' ]; ?>" alt="<?php echo $image[ 'alt' ]; ?>" />
							</div>
						</div>
						<div class="col-12 col-lg-8">
							<?php if( !empty( $bulletpoints_beschreibung ) ): ?>
								<div class="bulletpoints-beschreibung <?php if($currentBlogId == 28){echo " rz10Headline";}; ?>">
									<?php echo $bulletpoints_beschreibung; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if( !empty( $beschreibung ) ): ?>
						<div class="row">
							<div class="col-12">
								<?php echo $beschreibung; ?>
							</div>
						</div>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="cv-footer">
		<div class="container">
			<div class="row justify-content-center text-right mt-4">
				<div class="col-sm-12 col-md-8">
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
</div>
<script>
jQuery( function( $ ) {
	var setAffixPosition = function() {
		$( '.pardot-affix' ).css( 'top', Math.min( window.innerHeight - $( '.pardot-affix' ).height(), 0 ) );
	}

	// Die Funktion 'setAffixPosition' ausführen
	window.addEventListener('message', function(event) {
		if((event.origin.match(/^https?:\/\/((staging\d*\.|www2\.)?(mindsquare|innotalent|blog\.mindsquare|mindsquare|maint\-care|mind\-force|mind\-forms|erlebe\-software|activate\-hr|mission\-mobile|rz10|compamind|mind\-logistik|freelancercheck|customer-first-cloud)\.de|go\.pardot\.com)$/i) || event.origin == window.location.origin) && event.data && !isNaN(event.data)) {
			setAffixPosition();
		}
	});
	$( window ).on( 'resize', function() {
		setAffixPosition();
	});
	setAffixPosition();
});
</script>

<?php require( plugin_dir_path(__FILE__) . 'footer.php' ); ?>
