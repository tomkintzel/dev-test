<?php

// Getvaraiblen
$get_search = get_query_var( 'search' ) ?: '';
$get_paged = ( int ) ( get_query_var( 'paged' ) ?: 1 );

// Seiteninhalt
$logos = get_field( 'freelancer_logos', 'option' );
$posts_per_page = get_field( 'freelancer_posts_per_page', 'option' ) ?: 10;
$freelancer_ansprechpartner_position = ( get_field( 'freelancer_ansprechpartner_position', 'option' ) ?: 5 ) - 1;

// Begin: Ansprechpartner
$ansprechpartner_vorname = get_field( 'freelancer_vorname', 'option' );
$ansprechpartner_nachname = get_field( 'freelancer_nachname', 'option' );
$ansprechpartner_position = get_field( 'freelancer_position', 'option' );
$ansprechpartner_email = get_field( 'freelancer_email', 'option' );
$ansprechpartner_email_bewerbung = get_field( 'freelancer_email_bewerbung', 'option' );
$ansprechpartner_telefonnummer = get_field( 'freelancer_telefonnummer', 'option' );
$ansprechpartner_img = get_field( 'freelancer_img', 'option' );
// Ende: Ansprechpartner

$freelancer_clone_config_bg = get_field( 'freelancer_clone_config_bg', 'option' );

// Defaults
$default_background = array(
	'background' => null,
	'bg_color' => null,
	'text_color' => null,
	'transparent_background' => null,
	'image' => null,
	'image_size' => null,
	'parallax' => null,
	'mobile' => false,
	'mobile_size' => null
);

// Headereinstellungen
$config_background = wp_parse_args( $freelancer_clone_config_bg[ 'config-bg' ], $default_background );
$config_background_mobile = $config_background[ 'mobile' ] ? $config_background[ 'mobile_section' ] : $default_background;

// Abfrage erstellen
$post_query = new WP_Query( array(
	'post_type' => 'freelancer-projekte',
	'post_status' => 'publish',
	'posts_per_page' => $posts_per_page,
	'paged' =>  $get_paged,
	's' => $get_search
));
$posts_found = $post_query->found_posts;
if( $posts_found <= 0 ) {
	$post_query = new WP_Query( array(
		'post_type' => 'freelancer-projekte',
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged' =>  $get_paged
	));
}

if ( class_exists( 'Msq_Structured_Data' ) ) {
	new MSQ_Structured_Data_Item_List( $post_query->posts, $post_query->found_posts );
}

get_header();
?>
<main id="page-container" class="freelancer-projekt einstiegsseite page page-<?php the_ID(); ?>">
	<?php msq_add_section( array(
		'background' => $config_background[ 'background' ],
		'bg_color' => $config_background[ 'bg_color' ],
		'text_color' => $config_background[ 'text_color'],
		'transparent_background' => $config_background[ 'transparent_background' ],
		'background_image' => $config_background[ 'image' ],
		'image_size' => $config_background[ 'image_size' ],
		'parallax' => $config_background[ 'parallax' ],
		'mobile' => $config_background[ 'mobile' ],
		'mobile_size' => $config_background_mobile[ 'mobile_size' ],
		'mobile_background' => $config_background_mobile[ 'background' ],
		'mobile_bg_color' => $config_background_mobile[ 'bg_color' ],
		'mobile_text_color' => $config_background_mobile[ 'text_color' ],
		'mobile_transparent_background' => $config_background_mobile[ 'transparent_background' ],
		'mobile_background_image' => $config_background_mobile[ 'image' ],
		'mobile_image_size' => $config_background_mobile[ 'image_size' ],
		'headline' => get_the_title(),
		'ids' => 'header',
		'classes' => 'py-5',
		'content' => msq_add_module( 'acf-repeater', array(
			'items' => array(
				msq_add_layout( 'form', array(
					'class' => 'row justify-content-center align-items-end',
					'content' => msq_add_module( 'acf-repeater', array(
						'items' => array(
							'<div class="col-0 col-lg-3"></div>',
							msq_add_module( 'form-search', array(
								'class' => 'col-sm-7 col-md-6 col-lg-4',
								'placeholder' => 'Jetzt suchen und...',
								'name' => 'search',
								'value' => htmlspecialchars( $get_search, ENT_QUOTES, 'UTF-8' ),
								'btn-text' => '... Wunschprojekt finden <i class="fa fa-search" aria-hidden="true"></i>',
								'btn-class' => 'btn-success btn-block mt-4'
							), true ),
							msq_add_layout( 'wrapper', array(
								'class' => 'col-lg-3 d-none d-lg-flex match',
								'item-class' => 'match-item',
								'items' => array(
									$posts_found,
									'freie<strong>Projekte</strong>'
								)
							), true )
						)
					), true )
				), true ),
				msq_add_layout( 'wrapper', array(
					'class' => 'row justify-content-center logos mt-3',
					'item-class' => 'mt-3',
					'items' => array_merge(
						array( msq_add_layout( 'wrapper', array(
							'class' => 'd-lg-none match align-self-center',
							'item-class' => 'match-item',
							'items' => array(
								$posts_found,
								'freie<strong>Projekte</strong>'
							)
						), true ) ),
						msq_add_module( 'acf-repeater', array(
							'items' => $logos,
							'element' => 'acf-image-array',
							'mapcallback' => function( $item, $key ) {
								return array(
									'class' => 'col-auto d-flex',
									'image' => $item[ 'freelancer_img' ]
								);
							}
						))
					)
				), true )
			)
		), true )
	)); ?>
	<?php msq_add_section( array(
		'background' => 'mind-white-background',
		'classes' => 'mt-3 text-center',
		'content' => msq_add_layout( 'wrapper', array(
			'class' => 'row justify-content-center',
			'item-class' => 'col-lg-8 mind-light-gray-text',
			'items' => implode( ' | ', array(
				'<span class="mind-gray-text">Freelancer Jobbörse</span>',
				'<a href="https://mindsquare.de/freelancer/">Freelancer Übersicht</a>',
				'<a href="https://mindsquare.de/freelancer-freiberufler/">mindsquare für Freelancer</a>'
			))
		), true )
	)); ?>
	<?php if( $posts_found > 0 ) {
		msq_add_section( array(
			'background' => 'mind-white-background',
			'headline' => 'Freie Projekte',
			'classes' => 'py-5',
			'content' => msq_add_layout( 'row-equal', array(
				'class' => 'justify-content-center',
				'col-class' => 'col-sm-12 col-lg-8',
				'items' => array(
					msq_add_module( 'list-freelancer-projekt', array(
						'items' => array_slice( $post_query->posts, 0, $freelancer_ansprechpartner_position )
					), true ),
					msq_add_module( 'header-contact', array(
						'img' => $ansprechpartner_img,
						'title' => 'Ihr Ansprechpartner:',
						'name' => trim( $ansprechpartner_vorname . ' ' . $ansprechpartner_nachname ),
						'position' => $ansprechpartner_position,
						'email' => $ansprechpartner_email,
						'tel' => $ansprechpartner_telefonnummer,
						'btn-href' => 'mailto:' . $ansprechpartner_email_bewerbung . '?subject=Bewerbung%20Freelancer&body=Guten%20Tag%20Herr%20' . $ansprechpartner_nachname . '%2C',
						'btn-class' => 'btn btn-primary btn-sm',
						'btn-text' => '<i class="fa fa-rocket" aria-hidden="true"></i> Jetzt <strong>bewerben</strong>',
						'mobile' => true,
						'mobile-title' => 'Ihr Ansprechpartner für Fragen'
					), true ),
					msq_add_module( 'list-freelancer-projekt', array(
						'items' => array_slice( $post_query->posts, $freelancer_ansprechpartner_position )
					), true ),
					msq_add_module( 'page-navigation', array(
						'active' => $get_paged,
						'max_num_pages' => $post_query->max_num_pages
					), true )
				)
			), true )
		));
	}
	else {
		msq_add_section( array(
			'background' => 'mind-yellow-background',
			'headline' => 'Für Ihre Suchanfrage haben wir leider keine passenden Projektangebote gefunden.',
			'classes' => 'py-5',
			'content' => msq_add_layout( 'row-equal', array(
				'class' => 'justify-content-center',
				'col-class' => 'col-11 col-lg-8',
				'items' => array(
					'<p class="text-center">Hier kannst du dir die freien Projekte ansehen:</p>',
					msq_add_module( 'list-freelancer-projekt', array(
						'items' => $post_query->posts
					), true ),
					msq_add_module( 'page-navigation', array(
						'active' => $get_paged,
						'max_num_pages' => $post_query->max_num_pages
					), true )
				)
			), true )
		));
	} ?>
</main>
<?php
get_footer();
?>
