<?php
/**
 * Template Name: Conversion Page
 */
?>
 
<?php 
$background_image = get_field('cp_background_image');
$image = get_field('image');
$beschreibung = get_field('beschreibung');
$pardot_form_id = get_field( 'pardot' );
$pardot = msq_get_pardot_form( array(
	'form_id' => $pardot_form_id,
	'height' => '300px',
	'querystring' => htmlentities( $_SERVER[ 'QUERY_STRING' ], ENT_QUOTES ) . '&' . http_build_query( ['eventCategory' => 'conversionpage', 'eventLabel' => get_the_title()]  )
));
$pardot_title = get_field('pardot_titel');
$bullet_title = get_field('bullet_title');
$untertitel = get_field('untertitel');
$telefonnummer = get_field( 'cp_telefonnummer' ) ?: get_field( 'cp_telefonnummer', 'option' );
$email_adresse = get_field( 'cp_email_adresse' ) ?: get_field( 'cp_email_adresse', 'option' );
$autoren_beschreibung = get_field( 'autoren_beschreibung', get_the_ID(), false );

$previewvideo="<div class='centermedium' >".get_field('vorschau_video')."</div>";

if ( !empty( $image ) ) {
	if( !empty( $image['sizes']['reference_image'] ) ) {
		$thumb = $image['sizes'][$size];
	} else {
		$thumb = $image['url'];
	}
	$mediumleft="<img class='centermedium' src=".$thumb." alt=".$image['alt']." />";
	$mediumright=$previewvideo;
} else {
	$mediumleft=$previewvideo;
	$mediumright="";
}

$header_farbe = get_field( 'header_farbe' );
if( !empty( $header_farbe ) ) {
	$header_farbe = ' style="background-color:' . $header_farbe . ';"';
}
$innere_header_farbe = get_field( 'innere_header_farbe' );
if( !empty( $innere_header_farbe ) ) {
	$innere_header_farbe = ' style="background-color:' . $innere_header_farbe . ';"';
}

require( plugin_dir_path(__FILE__) . 'header.php' );
?>
<div id="cv-wrapper" class="template-conversionpage">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2">
			<?php if ( get_theme_mod( 'fb_logo' ) ) : ?>
					<div id="logo"><img src="<?php $image_thumb = wp_get_attachment_image_src( attachment_url_to_postid( get_theme_mod( 'fb_logo' ) ), 'msq_conversion_pages_logo' ); echo $image_thumb[0]; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></div>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row title-row title-bg-color"<?php echo $header_farbe; ?>>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 title-wrapper"<?php echo $innere_header_farbe; ?>>
						<h1 style="text-align: left;"><?php the_title(); ?></h1>
						<?php if (strlen($untertitel)>1): ?>
							<p><?php echo $untertitel; ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row cv-background-image "<?php echo ! empty( $background_image ) ? ' style="background-image: url(' . $background_image . ');"' : ''; ?>>
			<div class="container">
				<div class="row main row-eq-height">
					<div class="col-md-3 hidden-xs hidden-sm white-bg shadow-left">
						<div class="cv-image-wrapper">
							<?php echo $mediumleft; ?>
						</div>
						<?php if( !empty( $autoren_beschreibung ) ): ?>
							<div class="subline"><?php echo $autoren_beschreibung; ?></div>
						<?php endif; ?>
					</div>
					<div class="col-sm-6 col-md-5 col-xs-12 white-bg form-col">
						<h2><?php echo $pardot_title; ?></h2>
						<?php echo $pardot; ?>
					</div>
					<div class="col-sm-6 col-md-4 col-xs-12 white-bg shadow-right bullet-col">
						<div class="row bulletheader">
							<h3><?php echo $bullet_title ?></h3>
							<?php 
								if ($mediumright) {
									echo $mediumright;
								}
							?>
						
						<?php
							if (strlen($beschreibung)>0) {
								echo "<p>$beschreibung</p>";
							}
						?>
						</div>
						<?php 
							if( have_rows('bulletlist') ): ?>
							<div class="bulletlist">
								<ul>
								<?php 
									while ( have_rows('bulletlist') ) : the_row(); 
										$list_bullet_title = get_sub_field('bullet_title');
										$list_bullet_description = get_sub_field('bullet_description');
										?>
										<li class="bulletouter">
											<div class="bulletwrapper"><span class="glyphicon glyphicon-check bulleticon cv-icon-color"></span></div>
											<div class="bullettext">
												<p>
												<?php 
													if ( !empty( $list_bullet_title ) ) : ?>
														<strong><?php echo $list_bullet_title; ?></strong><br />
												<?php endif; 
													if ( !empty( $list_bullet_description ) ) : 
														echo $list_bullet_description;
													endif; ?>
												</p>
											</div>
										</li>
								<?php	endwhile; ?>
								</ul>
							</div>
						<?php
							endif;
						?>
					</div>
				</div>
				<div class="row person-wrapper">
					<div class="col-xs-12 hidden-md hidden-lg white-bg shadow-left">
						<div class="cv-image-wrapper">
							<?php echo $mediumleft; ?>
						</div>
						<?php if( !empty( $autoren_beschreibung ) ): ?>
							<div class="subline"><?php echo $autoren_beschreibung; ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="row trusted-row">
					<div class="col-xs-12 center-block" style="float: none;">
						<div class="row">
							<?php
							if( have_rows('trusted_icons','option') ):
								$trusted_icons=get_field('trusted_icons','option');
								$reverse_icons=array_reverse($trusted_icons);
								foreach( $reverse_icons as $subfield ) {
									$icon=$subfield["icon"]; ?>
									<div class="pull-right trusted-logo-container">
										<img class="trusted-logo" width='100%' src="<?php echo $icon['url']; ?>" />
									</div>
									<?php
								}
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 footer">
			 <p>
				<span>powered by mindsquare GmbH</span>
				<?php if (strlen($email_adresse)>1): ?>
				| <span><a href="mailto:<?php echo $email_adresse?>"><?php echo $email_adresse?></a></span>
				<?php endif; ?>
				<?php if (strlen($telefonnummer)>1): ?>
				| <span>Tel.: <a href="tel:<?php echo $telefonnummer?>"><?php echo $telefonnummer?></a></span>
				<?php endif; ?>
				| <span><a href="/impressum/">Impressum</a></span>
				| <span>Copyright <?php echo date('Y'); ?></span>
			 </p>
			</div>
		</div>
	</div>
</div>
<?php

require( plugin_dir_path(__FILE__) . 'footer.php' );

?>