<?php
	global $msqTheme;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<?php $msqTheme->getTemplatePart( 'template-parts/header/google_optimize' ); ?>
		<?php get_template_part( 'template-parts/header/tagmanager', 'head' ); ?>
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<meta name="theme-color" content="#f6ba00">
		<meta name="msapplication-navbutton-color" content="#f6ba00">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="#f6ba00">
		<title><?php wp_title('|'); ?></title>

		<link rel="icon" type="images/png" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.ico" />
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php get_template_part( 'template-parts/header/tagmanager', 'body' ); ?>
		<?php get_template_part( 'template-parts/header/masthead' ); ?>
		<?php if( !empty( $msqTheme ) )$msqTheme->getTemplatePart( 'template-parts/header/noscript' ); ?>
