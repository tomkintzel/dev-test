<?php
/**
 * Template Name: Schulungen
 */
?>

<?php
require_once('wp-content/themes/mindsquare/partials/inc-function.php');
// Bootstrap 3
wp_deregister_style('old-bootstrap');
// Bootstrap 4 hinzufügen
wp_enqueue_style( 'fb-bootstrap4' );
// Lade die Daten vom Conversion Page
$blog_id = get_current_blog_id();

$image_thumb = wp_get_attachment_image_src( attachment_url_to_postid( get_theme_mod( 'fb_logo' ) ), 'msq_conversion_pages_logo' );
$image_thumb_alt = esc_attr( get_bloginfo( 'name', 'display' ) );
$post_title = get_the_title();
$untertitel = get_field( 'untertitel' );
$image = get_field( 'schulungen_image' );
$mediumleft = "<img class='centermedium' src=" . $image[ 'url' ] . " alt=" . $image[ 'alt' ] . " />";
$subline_text = get_field( 'schulungen_autoren_subline' );
$pardot_title = get_field( 'pardot_titel' );
$pardot_form_id = get_field( 'pardot' );
$queryArray = array();
parse_str( $_SERVER[ 'QUERY_STRING' ], $queryArray );
// Dankesseite laden
$seminarCompletionPage = get_field( 'seminare_completion_page', 'option' );
if( $seminarCompletionPage ) {
	$queryArray[ 'redirect' ] = get_permalink( $seminarCompletionPage );
}
$bullet_title = get_field( 'bullet_title' );
$beschreibung = get_field( 'beschreibung' );
$bulletlist = get_field( 'schulungen_bulletlist' );
if( $trusted_icons = get_field( 'trusted_icons','option' ) ) {
	$trusted_icons = array_reverse( $trusted_icons );
}
$telefonnummer = get_field( 'cp_telefonnummer' ) ?: get_field( 'cp_telefonnummer', 'option' );
$email_adresse = get_field( 'cp_email_adresse' ) ?: get_field( 'cp_email_adresse', 'option' );
if( empty( $list_icon = get_field( 'schulungen_list_icon' ) ) ) {
	$list_icon = 'fa fa-check';
}

require( plugin_dir_path( __FILE__ ) . 'header.php' );

// Switch to mindsquare
if( $blog_id ) {
	switch_to_blog( 37 );
}

// Seminartyp ermitteln
$inhouse_seminar_anmelden = get_field( 'seminarpages_inhouse_seminar_conversionpage', 'option' );
$is_inhouse_seminar = $post->post_name == $inhouse_seminar_anmelden->post_name;

// Prüfe die Eingaben des Benutzers
if( isset( $_GET[ 'schulung' ] ) && ( $previousPage = get_page_by_path( urldecode( $_GET[ 'schulung' ] ), OBJECT, 'seminare' ) ) ) {
	// Seminar gefunden

	// Der Dankesseite eine ID übergeben
	if( !empty( $queryArray[ 'redirect' ] ) ) {
		$queryArray[ 'redirect' ] .= '?seminar-id=' . $previousPage->ID;
	}

	// Lade die Daten vom Seminar
	if( !empty( $dauer = get_field( 'anzahl_seminartage', $previousPage->ID ) ) ) {
		if( !preg_match( '/[^-\d]/', $dauer ) ) {
			$dauer_num = floatval( str_replace( ',', '.', $dauer) );
		}
	}
	if( $schwerpunkte = get_field( 'schwerpunkte', $previousPage ) ) { 
		if( preg_match_all( '/<li>(.*)<\/li>/i', $schwerpunkte, $schwerpunkte ) ) {
			$schwerpunkte = $schwerpunkte[ 1 ];
		}
		else {
			$schwerpunkte = null;
		}
	}
	if( $referent = get_field( 'referent', $previousPage->ID ) ) {
		if( $referent_img_src = get_field( 'großes_bild', $referent->ID ) ) {
			$referent_img_id = attachment_url_to_postid( $referent_img_src );
			$referent_img_alt = get_post_meta( $referent_img_id, '_wp_attachment_image_alt', true );
			$mediumleft = "<img class='centermedium' src=" . $referent_img_src . " alt=" . $referent_img_alt . " />";
		}
		$subline_text = get_field( 'einleitungstext', $referent->ID );
	}
	$untertitel = get_the_title( $previousPage );
	$bullet_title = "Auf einen Blick";
	if( $autor = get_field( 'referent', $previousPage->ID ) ) {
		$autor_name = ';' . urldecode( $autor->post_name );
	}
	else {
		$autor_name = '';
	}
	if( $is_inhouse_seminar ) {
		// Der Dankesseite eine ID übergeben
		if( !empty( $queryArray[ 'redirect' ] ) ) {
			$queryArray[ 'redirect' ] .= '&seminar-type=inhouse';
		}

		$preis = get_field( 'inhouse_preis', $previousPage->ID );
		if( !empty( $preis ) ) {
			if( !preg_match( '/[^-\d]/', $preis ) ) {
				$preis_num = floatval( str_replace( ',', '.', $preis ) );
				$preis = number_format( $preis_num, 0, ',', '.' );
			}
		}
		$angebotspreis = get_field( 'inhouse-angebotspreis', $previousPage->ID );
		if( !empty( $angebotspreis ) ) {
			if( !preg_match( '/[^-\d]/', $angebotspreis ) ) {
				$angebotspreis_num = floatval( str_replace( ',', '.', $angebotspreis ) );
				$angebotspreis = number_format( $angebotspreis_num, 0, ',', '.' );
			}
		}
		if( empty( $preis ) && !empty( $angebotspreis ) ) {
			$preis = $angebotspreis;
			$angebotspreis = false;
		}
	}
	else {
		$preis = get_field( 'preis', $previousPage->ID );
		if( !empty( $preis ) ) {
			if( !preg_match( '/[^-\d]/', $preis ) ) {
				$preis_num = floatval( str_replace( ',', '.', $preis ) );
				$preis = number_format( $preis_num, 0, ',', '.' );
			}
		}
		$angebotspreis = get_field( 'angebotspreis', $previousPage->ID );
		if( !empty( $angebotspreis ) ) {
			if( !preg_match( '/[^-\d]/', $angebotspreis ) ) {
				$angebotspreis_num = floatval( str_replace( ',', '.', $angebotspreis ) );
				$angebotspreis = number_format( $angebotspreis_num, 0, ',', '.' );
			}
		}
		if( empty( $preis ) && !empty( $angebotspreis ) ) {
			$preis = $angebotspreis;
			$angebotspreis = false;
		}
	}

	// Füge ein Euro-Zeichen hinzu
	if( strpos( $preis, '€' ) === false ) {
		$preis .= '&nbsp;€';
	}
	if( !empty( $angebotspreis ) && strpos( $angebotspreis, '€' ) === false ) {
		$angebotspreis .= '&nbsp;€';
	}

	// Semianrtyp unterscheiden
	if( $is_inhouse_seminar ) {
		$queryArray[ 'Seminar_Anmeldung' ] = urldecode( $previousPage->post_name ) . $autor_name . ';inhouse';
	}
	else {
		// Lade die Informationen über das Seminar
		$termine = get_field( 'seminartermine', $previousPage->ID );
		// Entferne alle vergangene Seminartermine
		$termine = array_filter( $termine, function( $value ) {
		  return strtotime( $value[ 'von_datum' ] ) > time();
		} );
		
		// Sortiert die Seminartermine nach Startdatum
		usort( $termine, function( $a, $b ) {
		  return strtotime( $a[ 'von_datum' ] ) - strtotime( $b[ 'von_datum' ] );
		} );

		// Wenn ein Termin angegeben wurde
		if( isset( $_GET[ 'termin' ] ) ) {
			foreach( $termine as $termin ) {
				// Wenn dieser Termin gefudnen wurde
				if( date( 'd.m.Y', strtotime( $termin[ 'von_datum' ] ) ) == $_GET[ 'termin' ] ) {
					$match = $termin;
					break;
				}
			}
		}

		// Wenn ein Termin gefunden wurde
		if( isset( $match ) ) {
			$queryArray[ 'Seminar_Anmeldung' ] = urldecode( $previousPage->post_name ) . $autor_name . ';' . $match[ 'von_datum' ];
			if( !empty( $dauer_num ) && $dauer_num > 1 ) {
				$bis_datum = date( 'd.m.Y', strtotime( $termin[ 'von_datum' ] . ' +' . ( $dauer_num - 1 ) . ' day') );
				$beschreibung = '<strong>Termin: ' . date( 'd.m.Y', strtotime( $match[ 'von_datum' ] ) ) . ' - ' . $bis_datum . '</strong>';
			}
			else {
				$beschreibung = '<strong>Termin: ' . date( 'd.m.Y', strtotime( $match[ 'von_datum' ] ) ) . '</strong>';
			}
		}
		else {
			$prePardotForm = '<p class="pardot-extern-form-field"><label class="pardot-extern-field-label" for="pardot-extern-seminar-field">Termin wählen *</label><select onchange="jQuery(\'iframe.pardotform\')[0].contentWindow.postMessage({type:\'SetValue\',fields:[{name:\'Seminar_Anmeldung\',value:jQuery(this).find(\':selected\').val()}]}, \'*\')" id="pardot-extern-seminar-field" class="pardot-extern-field-select"><option selected disabled value="">Wählen Sie einen Termin aus</option>';
			if( !empty( $dauer_num ) && $dauer_num > 1 ) {
				foreach( $termine as $termin ) {
					$prePardotForm .= '<option value="' . urldecode( $previousPage->post_name ) . $autor_name . ';' . $termin[ 'von_datum' ] . '">' . $termin[ 'von_datum' ] . ' - ' . date( 'd.m.Y', strtotime( $termin[ 'von_datum' ] . ' +' . ( $dauer_num - 1 ) . ' day' ) ) . '</option>';
				}
			}
			else {
				foreach( $termine as $termin ) {
					$prePardotForm .= '<option value="' . urldecode( $previousPage->post_name ) . $autor_name . ';' . $termin[ 'von_datum' ] . '">' . $termin[ 'von_datum' ] . '</option>';
				}
			}
			$prePardotForm .= '</select></p>';
		}
	}

	// Füge den Title der Schulung hinzu
	$queryArray[ 'Post_Title' ] = html_entity_decode( $untertitel, ENT_NOQUOTES, 'UTF-8' );
	$queryArray['eventLabel'] = $queryArray[ 'Post_Title' ];
}
else {
	$seminare = get_posts( array(
		'post_type' => 'seminare',
		'numberposts' => -1
	) );

	// Nur wenn Schulungen gefunden
	if( !empty( $seminare ) ) {
		$prePardotForm = '<p class="pardot-extern-form-field"><label class="pardot-extern-field-label" for="pardot-extern-seminar-field">Schulung wählen *</label><select onchange="jQuery(\'iframe.pardotform\')[0].contentWindow.postMessage({type:\'SetValue\',fields:[{name:\'Seminar_Anmeldung\',value:jQuery(this).find(\':selected\').val()},{name:\'redirect\',value:jQuery(this).find(\':selected\').data(\'redirect\')},{name:\'Post_Title\',value:jQuery(this).find(\':selected\').data(\'title\')}]}, \'*\')" id="pardot-extern-seminar-field" class="pardot-extern-field-select"><option selected disabled value="">Wählen Sie eine Schulung aus</option>';
		$redirect = '';
		foreach( $seminare as $seminar ) {
			if( !empty( $queryArray[ 'redirect' ] ) ) {
				$redirect = urldecode( $queryArray[ 'redirect' ] ) . '?seminar-id=' . $seminar->ID;
			}

			$seminar_title = get_the_title( $seminar );
			if( $autor = get_field( 'referent', $seminar->ID ) ) {
				$autor_name = ';' . urldecode( $autor->post_name );
			}
			else {
				$autor_name = '';
			}
			if( $is_inhouse_seminar ) {
				if( !empty( $queryArray[ 'redirect' ] ) ) {
					$redirect .= '&seminar-type=inhouse';
				}
				$prePardotForm .= '<option value="' . urldecode( $seminar->post_name ) . $autor_name . ';inhouse" data-redirect="' .  $redirect . '" data-title="' . $seminar->post_title . '">' . $seminar_title . '</option>';
			}
			else {
				$prePardotForm .= '<option value="' . urldecode( $seminar->post_name ) . $autor_name . '" data-redirect="' .  $redirect . '" data-title="' . $seminar->post_title . '">' . $seminar_title . '</option>';
			}
		}
		$prePardotForm .= '</select></p>';
	}
}
$queryArray['eventCategory'] = 'schulung';

// Switch to mindsquare
if( $blog_id ) {
	restore_current_blog();
}

$pardot = msq_get_pardot_form( array(
	'form_id' => $pardot_form_id,
	'height' => '300px',
	'class' => 'seminar',
	'querystring' => http_build_query( $queryArray, null, '&', PHP_QUERY_RFC3986 )
));

// Switch to mindsquare
if( $blog_id ) {
	switch_to_blog( 37 );
}
?>
<link rel="stylesheet" href="/wp-content/themes/mindsquare/templates/mindsquare-bootstrap.css?ver=<?php echo filemtime( get_theme_root() . '/mindsquare/templates/mindsquare-bootstrap.css' ); ?>">
<link rel="stylesheet" href="/wp-content/themes/mindsquare/css/seminare.css?ver=<?php echo filemtime( get_theme_root() . '/mindsquare/css/seminare.css' ); ?>">

<div id="cv-wrapper" class="seminar-page template-schulungen">
	<div class="row">
		<div class="col-sm-2">
<?php if( !empty( $image_thumb ) ): ?>
			<div id="logo"><img src="<?php echo $image_thumb[ 0 ]; ?>" alt="<?php echo $image_thumb_alt; ?>"/></div>
<?php endif; ?>
		</div>
	</div>
	<div class="background-img"></div>
	<div class="container">
		<div class="row title-row">
			<div class="col-xs-12 title-wrapper">
				<h1 style="text-align: left;"><?php echo $post_title ?></h1>
	<?php if( !empty ( $untertitel ) ): ?>
				<p><?php echo $untertitel; ?></p>
	<?php endif; ?>
			</div>
		</div>
		<div class="row main">
			<div class="col-lg-3 hidden-md hidden-sm hidden-xs white-bg shadow-left">
				<?php if( !empty( $mediumleft ) ): ?>
				<div class="cv-image-wrapper">
					<?php echo $mediumleft; ?>
				</div>
				<?php endif; ?>
				<strong class="headline">Ihr Referent</strong>
				<div class="subline"><?php echo $subline_text; ?></div>
			</div>
			<div class="col-sm-12 col-md-6 col-xl-5 col-xs-12 white-bg form-col">
				
				<?php if( !empty( $pardot_title ) ): ?>
					<h2><?php echo $pardot_title; ?></h2>
				<?php endif;
					if( !empty( $prePardotForm ) ) {
						echo $prePardotForm;
					}
					echo $pardot;
				?>
			</div>
			<div class="col-sm-12 col-md-6 col-xl-4 col-xs-12 white-bg shadow-right bullet-col">
				<div class="row bulletheader">
					<?php if( !empty( $bullet_title ) ): ?>
					<h3><?php echo $bullet_title ?></h3>
					<?php endif;
						if( !empty( $beschreibung ) ) {
							echo '<p>' . $beschreibung . '</p>';
						} ?>
				</div>
				<?php if( !empty( $schwerpunkte ) ): ?>
				<div class="row bulletlist">
					<ul>
						<?php foreach( $schwerpunkte as $schwerpunkt ): ?>
						<li class="bulletouter">
							<div class="bulletwrapper"><span class="<?php echo $list_icon; ?> bulleticon cv-icon-color"></span></div>
							<div class="bullettext">
								<p><?php echo $schwerpunkt; ?></p>
							</div>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<?php elseif( !empty( $bulletlist ) ): ?>
				<div class="row bulletlist">
					<ul>
					<?php foreach( $bulletlist as $bullet ): ?>
						<li class="bulletouter">
							<div class="bulletwrapper"><span class="<?php echo $list_icon; ?> bulleticon cv-icon-color"></span></div>
							<div class="bullettext">
								<p>
								<?php 
									if ( !empty( $bullet[ 'schulungen_bullet_title' ] ) ) : ?>
										<strong><?php echo $bullet[ 'schulungen_bullet_title' ]; ?></strong><br />
								<?php endif; 
									if ( !empty( $bullet[ 'schulungen_bullet_description' ] ) ) : 
										echo $bullet[ 'schulungen_bullet_description' ];
									endif; ?>
								</p>
							</div>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<?php if( !empty( $angebotspreis ) ): ?>
					<div class="row bulletfooter">
						<strong>Preis: <s><?php echo $preis; ?></s><br /> <?php echo $angebotspreis; ?></strong>
					</div>
				<?php elseif( !empty( $preis ) ): ?>
					<div class="row bulletfooter">
						<strong>Preis: <?php echo $preis; ?></strong>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="row referent-wrapper">
			<div class="col-xs-12 hidden-lg white-bg shadow-left">
				<div class="outer">
					<div class="inner">
						<?php if( !empty( $mediumleft ) ): ?>
						<div class="cv-image-wrapper">
							<?php echo $mediumleft; ?>
						</div>
						<?php endif; ?>
						<strong class="headline">Ihr Referent</strong>
						<div class="subline"><?php echo $subline_text; ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row trusted-row">
			<div class="col-xs-12 center-block" style="float: none;">
				<div class="row">
					<?php if( $trusted_icons )foreach( $trusted_icons as $subfield ): ?>
					<div class="pull-right trusted-logo-container">
						<img class="trusted-logo" width='100%' src="<?php echo $subfield[ 'icon' ][ 'url' ]; ?>" />
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

<?php msg_add_section( 'seminare-usp' ); ?>

	<section class="text-section">
	  <div class="container no-wrapper">
		<div class="row">
		  <div class="col-xs-12">
			<h2>Unsere Kunden</h2>
		  </div>
		</div>
	  </div>
	</section>

<?php if ( wp_style_is( 'slick', 'registered' ) ) wp_enqueue_style( 'slick' );
	if ( wp_style_is( 'slick-theme', 'registered' ) ) wp_enqueue_style( 'slick-theme' );
	if ( wp_script_is( 'slick-script', 'registered' ) ) wp_enqueue_script( 'slick-script' );
	msg_add_section( 'seminare-brand' );
	msg_add_section( 'seminare-feedback' ); ?>

	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 footer">
		 <p>
			<span>powered by mindsquare GmbH</span>
			<?php if( !empty( $email_adresse ) ): ?>
			| <span><a href="mailto:<?php echo $email_adresse?>"><?php echo $email_adresse?></a></span>
			<?php endif; ?>
			<?php if( !empty( $telefonnummer ) ): ?>
			| <span>Tel.: <a href="tel:<?php echo $telefonnummer?>"><?php echo $telefonnummer?></a></span>
			<?php endif; ?>
			| <span><a href="/impressum/">Impressum</a></span>
			| <span>Copyright <?php echo date('Y'); ?></span>
		 </p>
		</div>
	</div>
</div>
<script>
jQuery(window).on('load',function() {
	if(jQuery('#pardot-extern-seminar-field').length > 0) {
		jQuery('iframe.pardotform').on('load', function() {
			var selectedField = jQuery('#pardot-extern-seminar-field').find(':selected');
			var fields = [];
			if(jQuery(selectedField).val()) {
				fields.push({
					name: 'Seminar_Anmeldung',
					value: jQuery(selectedField).val()
				});
			}
			if(jQuery(selectedField).data('redirect')) {
				fields.push({
					name: 'redirect',
					value: jQuery(selectedField).data('redirect')
				});
			}
			if(jQuery(selectedField).data('title')) {
				fields.push({
					name: 'Post_Title',
					value: jQuery(selectedField).data('title')
				});
			}
			if(fields.length > 0) {
				jQuery('iframe.pardotform')[0].contentWindow.postMessage({
					type: 'SetValue',
					fields: fields
				}, '*');
			}
		});
	}
});
</script>

<?php
if( $blog_id ) {
	restore_current_blog();
}
require( plugin_dir_path( __FILE__ ) . 'footer.php' );
?>
