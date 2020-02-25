<?php
	global $msqTheme;
?>
<!DOCTYPE html>
 <html <?php language_attributes(); ?>>
 <head>
 <!--[if lt IE 9]>

 <script src="/wp-content/themes/ms_basis_theme/dist/html5shiv.js"></script>
 <![endif]-->
 <!--[if lte IE 7]> <html class="ie7"> <![endif]-->
 <!--[if IE 8]>     <html class="ie8"> <![endif]-->
 <!--[if IE 9]>     <html class="ie9"> <![endif]-->
 <!--[if !IE]><!--> <html>             <!--<![endif]-->

 <meta charset="<?php bloginfo( 'charset' ); ?>" />
 <?php get_template_part( 'template-parts/header/tagmanager', 'head' ); ?>
 <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
 <title> <?php echo get_the_title(); ?></title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
              integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url('', __FILE__); ?>/conversionpage.css?ver=<?php echo filemtime( plugin_dir_path( __FILE__ ) . '/conversionpage.css'); ?>" />
 <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php if( file_exists( get_template_directory() . '/images/favicon.png' ) ): ?>
		<link rel="icon" type="images/png" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png">
	<?php endif; ?>
	<?php global $dfd_sunday; if( !empty( $dfd_sunday[ 'custom_favicon' ][ 'url' ] ) ): ?>
			<link rel="icon" type="image/png" href="<?php echo esc_url( $dfd_sunday[ 'custom_favicon' ][ 'url' ] ); ?>" />
	<?php endif; ?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<?php if( class_exists( 'Msq_Structured_Data' ) ) {
		new MSQ_Structured_Data_Web_Page();
	} ?>
	 <?php
	 wp_enqueue_script( 'slick' );
	 wp_head(); ?>
 </head>
 <body <?php body_class(); ?>>
	<?php get_template_part( 'template-parts/header/tagmanager', 'body' ); ?>
	<?php if( !empty( $msqTheme ) )$msqTheme->getTemplatePart( 'template-parts/header/noscript' ); ?>
