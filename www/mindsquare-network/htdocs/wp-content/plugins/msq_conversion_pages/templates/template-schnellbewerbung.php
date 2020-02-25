<?php
/**
 * Template Name: MSQ-Schnellbewerbung
 */

// Formular
$pardot_id = get_field( 'pardot' );
$query_array = array();
parse_str( $_SERVER[ 'QUERY_STRING' ], $query_array );
// Übergebe das Stellenangebot an Pardot
if( !empty( $_GET[ 'stellenangebot' ] ) ) {
	$stellenangebot = get_page_by_path( urldecode( $_GET[ 'stellenangebot' ] ), OBJECT, 'employment_notice' );
	if( $stellenangebot ) {
		$query_array[ 'Kommentar' ] = html_entity_decode( get_the_title( $stellenangebot ), ENT_NOQUOTES, 'UTF-8' );
		$query_array[ 'eventLabel' ] = $query_array[ 'Kommentar' ];
	}
}
$query_array[ 'eventCategory' ] = 'Schnellbewerbung';

$pardot = msq_get_pardot_form( array(
	'form_id' => $pardot_id,
	'height' => '400px',
	'querystring' => http_build_query( $query_array, null, '&', PHP_QUERY_RFC3986 ),
));

// Stichpunkte
$trusted_icons = get_field( 'trusted_icons' );

// Allgemein
$title = get_the_title();
$image_thumb = wp_get_attachment_image_src( attachment_url_to_postid( get_theme_mod( 'fb_logo' ) ), 'msq_conversion_pages_logo' );
$image_thumb_alt = esc_attr( get_bloginfo( 'name', 'display' ) );

require( plugin_dir_path(__FILE__) . 'header.php' );
?>
	<div id="cv-wrapper" class="template-schnellbewerbung">
		<div class="Schnellbewerbung-Content">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-lg-10 mb-lg-4">
						<div class="Schnellbewerbung-Headline">
							<div class="Schnellbewerbung-Logo">
								<?php if( !empty( $image_thumb ) ): ?>
									<div id="logo"><img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/></div>
								<?php endif; ?>
							</div>
							<div class="Schnellbewerbung-HeadlineTitle">
								<h1><?php echo $title; ?></h1>
							</div>
							<div class="Schnellbewerbung-HeadlineDescription">
								Fülle das nachfolgende Formular aus und bewirb dich für deinen neuen Job!
							</div>
						</div>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-12 col-lg-10 Schnellbewerbung-PardotCol">
						<div id="pardot-form">
							<?php if ( ! empty( $pardot ) ) {
								echo $pardot;
							} ?>
						</div>
						<div class="Schnellbewerbung-PardotFooter d-none d-lg-block">
							<strong>Du hast noch Fragen?</strong>
							<p>Wende dich gerne an unseren Personalleiter Timm Funke | per E-Mail an <a href="mailto:t.funke@mindsquare.de">t.funke@mindsquare.de</a> oder per Telefon an die <a href="tel:05219234593994">0521/92345 939-94</a>.</p>
						</div>
					</div>
					<div class="col-12 col-lg-1 Schnellbewerbung-TrustedIcons Schnellbewerbung-TrustedIcons-column">
						<?php if( ! empty( $trusted_icons ) ) : ?>
							<?php foreach( $trusted_icons as $trusted_icon ) :?>
								<img src="<?php echo $trusted_icon[ 'icon' ][ 'url' ]; ?>" alt="<?php echo $trusted_icon[ 'icon' ][ 'alt' ]; ?>" />
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="Schnellbewerbung-TrustedIcons">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-auto">
						<?php if( ! empty( $trusted_icons ) ) : ?>
							<?php foreach( $trusted_icons as $trusted_icon ) :?>
								<img src="<?php echo $trusted_icon[ 'icon' ][ 'url' ]; ?>" alt="<?php echo $trusted_icon[ 'icon' ][ 'alt' ]; ?>" />
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="Schnellbewerbung-Footer">
			<div class="container">
				<div class="row">
					<div class="col text-center">
						<p><a href="/impressum/" target="_blank" rel="noopener">Impressum</a> | <a href="//mindsquare.de/agb/" target="_blank" rel="noopener">AGB</a></p>
						<p>Copyright <?php echo date('Y'); ?> mindsquare GmbH</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
require( plugin_dir_path(__FILE__) . 'footer.php' );
?>
