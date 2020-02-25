<?php

require_once( __DIR__ . '/../ms_root/functions.php' );
require_once( get_theme_root() . '/ms_rz10_nineteen/inc/acf/field_postcard-dataset.php' );
locate_template('seminare.php', true);
locate_template('advisor.php', true);
locate_template('employment-notice.php', true);

require_once('partials/inc-function.php');
require_once( 'inc/templates/inc-function.php' );
require_once( 'inc/class-theme.php' );

include_once('inc/fb-acf-to-taxonomy.php');

remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

/**
 * Diese Funktion wird ausgeführt, wenn eine Karriere-Seite geöffnet wurde.
 * Bei den Karriere-Seiten soll dabei ein anderer Inhalt bei der Hauptnavigation
 * ausgespielt werden.
 *
 * @param string $name
 * @return string
 */
function msq_employment_notice_navigation( $name ) {
	global $post;

	if( is_single() ) {
		$is_karriere = $post->post_type == 'employment_notice';
	} else if( is_page() ) {
		$template = get_page_template_slug( $post->ID );
		$is_karriere = in_array( $template, array(
			'page-custom-with-mobile-cta.php',
			'templates/template-page-builder-karriere.php',
			'templates/template-it-career.php',
			'templates/template-page-builder-it-career.php',
			'templates/template-employment-notice-einstiegsseite.php',
			'templates/danke-karrieredownload.php'
		) );
		if( !$is_karriere ) {
			$seiten_mit_karriere_navigation = get_field( 'seiten_mit_karriere-navigation', 'option' );
			$is_karriere = in_array( $post->ID, $seiten_mit_karriere_navigation );
		}
	} else {
		$is_karriere = is_post_type_archive( 'career-event' );
	}
	if( !empty( $post->ID) && !$is_karriere ) {
		$seiten_mit_karriere_navigation = get_field( 'seiten_mit_karriere-navigation', 'option' );
		$is_karriere = in_array( $post->ID, $seiten_mit_karriere_navigation );
	}
	$kategorien_mit_karriere_navigation = get_field('kategorien_mit_karriere-navigation', 'option');
	if($kategorien_mit_karriere_navigation){
		$post_category = get_the_category($post->ID);
		$post_cat_id = $post_category[0]->cat_ID;
		$is_karriere = in_array($post_cat_id, $kategorien_mit_karriere_navigation);
	}
	if( $is_karriere ) {
		return 'career';
	}
	return $name;
}
add_filter( 'msq_navigation', 'msq_employment_notice_navigation' );

/**
 * Diese Funktion wird von der Navigation im Header verwendet, damit die richtige
 * Anzahl von freuen Stellen automatisch ausgeben wird.
 * @date 28.06.2019 Funktion wird auskommentiert, da nicht mehr die Anzahl Stellen in der Navigation angezeigt werden sollen!
 *
 * @param string $description
 * @param WP_Post $item
 * @return string
 *
function msq_employment_notice_navigation_description( $description, $item ) {
	if( $item->title == 'Karriere' ) {
		$countPosts = wp_count_posts( 'employment_notice' );
		return $countPosts->publish . ' freie Stellen';
	}
	return $description;
}
add_filter( 'msq_navigation_description', 'msq_employment_notice_navigation_description', 10, 2 ); */

/**
 * Diese Funktion erstellt neue Navigationen.
 */
function msq_register_navigation() {
	register_nav_menus([
		'karriere_primary_navigation' => __('Haupt-Navigation (Karriere)')
	]);
	register_nav_menus([
		'primary_navigation' => __('Haupt-Navigation')
	]);
	register_nav_menus([
		'footer_menu' => __('Footer navigation')
	]);
}
add_action( 'after_setup_theme', 'msq_register_navigation' );

/**
 * Diese Funktion erstellt neue Einstellungen neue Felder für die Hauptnavigation.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function msq_customize_register_navigation( $wp_customize ) {
	$wp_customize->add_setting( 'small_header_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'small_header_logo', array(
		'label' => 'Kleines Logo Webseite',
		'section' => 'title_tagline',
		'settings' => 'small_header_logo',
	)));

	$wp_customize->add_setting( 'white_logo' );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'white_logo', [
		'label'    => 'Weißes Logo',
		'section'  => 'title_tagline',
		'priority' => 20,
	] ) );
}
add_action( 'customize_register', 'msq_customize_register_navigation', 9 );

if ( ! function_exists( 'unregister_post_type' ) ) :
    function unregister_post_type( $post_type ) {
        global $wp_post_types;
        if ( isset( $wp_post_types[ $post_type ] ) ) {
            unset( $wp_post_types[ $post_type ] );
            return true;
        }
        return false;
    }
endif;

function msq_unregister_post_type () {
    unregister_post_type( 'my-product' );
    unregister_post_type( 'author' );
}
add_action( 'init', 'msq_unregister_post_type' );

/**
 * Dieser Filter wird vom "optinmonster"-Plugin ausgeführt. Wenn
 * die Variable $init eine Null übergeben wurde, dann wird OptinMonster nicht
 * ausgeführt.
 *
 * Diese Funktion ist mehrfach vorhanden:
 * @see wp-content/themes/ms_basis_theme/functions.php
 *
 * @param mixed $init
 * @param int $post_id Die aktuelle ID vom Post
 * @return mixed $init
 */
function msq_filter_optin_monster_api_final_output( $init, $post_id ) {
	$blacklist = array(
		'downloads',
		'conversionpages',
		'employment_notice'
	);
	if( !empty( $post_id ) && in_array( get_post_type( $post_id ), $blacklist ) ) {
		return null;
	}
	return $init;
}
add_filter( 'optin_monster_api_final_output', 'msq_filter_optin_monster_api_final_output', 10, 2 );

function my_remove_meta_boxes() {
	remove_meta_box( 'postexcerpt', 'post', 'normal');
    remove_meta_box( 'postexcerpt', 'page', 'normal');
    $blacklist = array(
		'seminare',
		'conversionpages',
		'advisor',
		'employment_notice',
		'career-event',
		'downloads',
		'freelancer-projekte',
		'knowhow',
		'kundenstimmen',
		'tagesseminare',
		'webinare',
        'post',
        'page'
	);
	foreach( $blacklist as $post_type ) {
		remove_meta_box( 'rocket_post_exclude', $post_type, 'side' );
		remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
	}
}
add_action( 'do_meta_boxes', 'my_remove_meta_boxes' );

/**
 * Diese Funktion entfernt die Update Meldung, die im Backend ganz oben angezeigt wird.
 * Wenn ein Update gemacht werden soll, dann über die Einstellungen.
 */
function msq_action_remove_update_nag() {
	remove_action( 'admin_notices', 'update_nag', 3 );
}
add_action( 'admin_head', 'msq_action_remove_update_nag' );

/**
 * Diese Funktion wird für eine Überprüfung der Posts im Backend ausgeführt.
 * Sollte ein Fehler gefunden werden, so wird eine Nachricht erzeugt und
 * als Fehlermeldung im oberen Bereich ausgegeben.
 */
function msq_action_backend_check() {
	global $post;
	if( is_admin() ) {
		if( !empty( $post ) && get_current_screen()->id == 'post' ) {
			// Beiträge
			if( $post->post_type == 'post' ) {
				$categories = wp_get_post_categories( $post->ID );
				$count_categories = count( $categories );
				$default_category = get_option( 'default_category' );
				if( $count_categories == 1 && in_array( $default_category, $categories ) ) {
					$msg = '<strong>Fehler:</strong> Bist du dir sicher, dass der Beitrag in der Standard Kategorie veröffentlicht werden soll?';
				}
				else if( $count_categories > 1 ) {
					$msg = '<strong>Fehler:</strong> Bist du sicher, dass du mehr als 2 Kategorien benötigst? Denn in den meisten Fällen ist nur eine Kategorie sinnvoll! Es hat <strong>KEINEN VORTEIL</strong> wenn der Beitrag in mehreren Kategorien ist!!!!';
				}
			}

			// Gebe die Nachrichten aus
			if( !empty( $msg ) ) {
				?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo $msg; ?></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Nachricht verbergen.</span>
						</button>
					</div>
				<?php
			}
		}
		// Weitere Fehlermeldungen
		$userId = get_current_user_id();
		$adminNotices = get_user_meta( $userId, 'admin_notices', true );
		if( !empty( $adminNotices ) && is_wp_error( $adminNotices ) ) {
			delete_user_meta( $userId, 'admin_notices' );
			$notices = $adminNotices->get_error_messages();
			if( !empty( $notices ) ) {
				foreach( $notices as $notice ) {
					?>
						<div class="notice notice-error is-dismissible">
							<p><?php echo $notice; ?></p>
							<button type="button" class="notice-dismiss">
								<span class="screen-reader-text">Nachricht verbergen.</span>
							</button>
						</div>
					<?php
				}
			}
		}
	}
}
add_action( 'admin_notices', 'msq_action_backend_check' );

/**
 * Diese Funktion entfernt vom TinyMCE Editor die "H1 Überschrift"-Möglichkeit.
 * @param mixed $init TinyMCE Parameter
 * @return mixed $init Die veränderten TinyMCE Parameter
 */
function msq_filter_tiny_mce_before_init( $init ) {
	$init[ 'block_formats' ] = 'Absatz=p;Überschrift 2=h2;Überschrift 3=h3;Überschrift 4=h4;Überschrift 5=h5;Überschrift 6=h6;Preformatted=pre';
	return $init;
}
add_filter( 'tiny_mce_before_init', 'msq_filter_tiny_mce_before_init' );

/**
 */
theme()->run();

/**
 * Diese Funktion registriert einige CSS-/JS-Dateien.
 */
function msq_register_styles_and_scripts() {
	// Style
	wp_register_style( 'font-awesome', get_stylesheet_directory_uri() . '/assets/css/font-awesome.min.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/font-awesome.min.css' ) );
	wp_register_style( 'old-bootstrap',  get_stylesheet_directory_uri() . '/assets/css/bootstrap.3.3.5.min.css', array(), '3.3.5' );
	wp_register_style( 'seminare', get_stylesheet_directory_uri() . '/css/seminare.css', array( 'old-bootstrap' ), filemtime( get_stylesheet_directory() . '/css/seminare.css' ) );
	wp_register_style( 'webinare', get_stylesheet_directory_uri() . '/css/seminare.css', array( 'old-bootstrap' ), filemtime( get_stylesheet_directory() . '/css/seminare.css' ) );
	wp_register_style( 'mindsquare-bootstrap', get_stylesheet_directory_uri() . '/templates/mindsquare-bootstrap.css', array('old-bootstrap' ),  filemtime( get_stylesheet_directory() . '/templates/mindsquare-bootstrap.css' ) );
	wp_register_style( 'stylesheet-style', get_stylesheet_directory_uri() . '/style.min.css', array(), filemtime( get_stylesheet_directory() . '/style.min.css' ) );
	wp_register_style( 'admin-style', get_stylesheet_directory_uri() . '/assets/css/admin.min.css', array(), filemtime( get_stylesheet_directory() . '/assets/css/admin.min.css' ) );
	wp_register_style( 'bricklayer', get_stylesheet_directory_uri() . '/assets/css/bricklayer.min.css', array(), '0.4.3' );

	// Skript
	wp_register_script( 'slick', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), '1.8.1', true );
	wp_register_script( 'bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array(), filemtime( get_stylesheet_directory() . '/assets/js/bootstrap.bundle.min.js' ), true );
	wp_register_script( 'old-bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.3.3.5.min.js', array( 'jquery' ), '3.3.5', true );

	msq_register_script_filemtime( 'custom-event-polyfill', '/assets/js/custom-event-polyfill.min.js', [], true);
	msq_register_script_filemtime( 'scoped-query-selector-shim', '/assets/js/scopedQuerySelectorShim.min.js', [], true );

	wp_register_script(
			'bricklayer',
			get_stylesheet_directory_uri() . '/assets/js/bricklayer.min.js',
			[ 'custom-event-polyfill', 'scoped-query-selector-shim' ],
			'0.4.3',
			true
	);
}
add_action( 'init', 'msq_register_styles_and_scripts' );

function msq_register_script_filemtime($handle, $src, $deps, $in_footer) {
	return wp_register_script(
			$handle,
			get_stylesheet_directory_uri() . $src,
			$deps,
			filemtime( get_stylesheet_directory() . $src ),
			$in_footer
	);
}

/**
 * Diese Funktion enqueued einige wichtige CSS-/JS-Dateien.
 */
function msq_enqueue_styles_and_scripts() {
	wp_enqueue_style( 'font-awesome' );
	if( !is_admin() && $GLOBALS[ 'pagenow' ] != 'wp-login.php' ) {
		wp_enqueue_style( 'stylesheet-style' );
		wp_enqueue_script( 'bootstrap' );
	}
}
add_action( 'wp_enqueue_scripts', 'msq_enqueue_styles_and_scripts' );

/**
 * Diese Funktion enqueued einige wichtige CSS-/JS-Dateien für den Backend.
 */
function msq_enqueue_admin_styles_and_scripts() {
	wp_enqueue_style( 'admin-style' );
}
add_action( 'admin_enqueue_scripts', 'msq_enqueue_admin_styles_and_scripts' );

function meintitel_shortcode()
{
    return the_title('<h1>', '</h1>');
}

add_shortcode('mein-titel', 'meintitel_shortcode');

function get_post_function($atts)
{
    extract(shortcode_atts(array('id' => '',), $atts));
    if (isset($id)) {
        $post = get_post($id, ARRAY_A);
        return do_shortcode($post['post_content']);
    }
}

add_shortcode('get_post_content', 'get_post_function');

function enqueue_seminare_scripts() {
	wp_deregister_style( 'font-awesome' );  // wp-content\plugins\js_composer\include\classes\core\class-vc-base.php:576
	wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.7.0' );
	wp_register_style('mindsquare-bootstrap', get_stylesheet_directory_uri() . '/templates/mindsquare-bootstrap.css', array(),  filemtime(get_stylesheet_directory() . '/templates/mindsquare-bootstrap.css'));

  if(is_page_template(array('templates/template-employment-notice-einstiegsseite.php', 'templates/template-webinarseite.php', 'templates/seminare-einstiegsseite.php', 'templates/template-seminarseite.php', 'templates/template-seminaruebersicht.php')) || is_tax(array( 'seminarkategorie', 'unternehmens-kategorie' ))) {
    // Lade Style-Dateien
    wp_enqueue_style('bootstrap',  get_stylesheet_directory_uri() . '/assets/css/bootstrap.3.3.5.min.css', array(), '3.3.5' );
    wp_enqueue_style('mindsquare-bootstrap');
    // Lade Script-Dateien
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.3.3.5.min.js', array( 'jquery' ), '3.3.5', true );
  }
  else if(is_singular(array('advisor'))) {
    // Lade Style-Dateien
    wp_enqueue_style('bootstrap',  get_stylesheet_directory_uri() . '/assets/css/bootstrap.3.3.5.min.css', array(), '3.3.5' );
    wp_enqueue_style('seminare', get_stylesheet_directory_uri() . '/css/seminare.css', array('bootstrap'), filemtime(get_stylesheet_directory() . '/css/seminare.css'));
    // Lade Script-Dateien
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/assets/js/bootstrap.3.3.5.min.js', array( 'jquery' ), '3.3.5', true );
  }
}
add_action('wp_enqueue_scripts', 'enqueue_seminare_scripts');

/*
 * Um Skripte asynchron zu laden, muss diesen ein #asyncload bei der $src hinzugefügt werden + die Versionsnummer muss auf null gesetzt werden.
 */
function msq_include_scripts()
{
    wp_enqueue_script( 'pardot-resizing', get_stylesheet_directory_uri() . '/js/initFrameResizing.js', array(), filemtime( get_stylesheet_directory() . '/js/initFrameResizing.js' ) );
	wp_register_script( 'typed', get_stylesheet_directory_uri() . '/assets/js/typed.min.js', array(), filemtime( get_stylesheet_directory() . '/assets/js/typed.min.js' ) );
    wp_register_script( 'slider-brand', get_stylesheet_directory_uri() . '/assets/js/layout/slider-brand.js', array( 'slick' ), filemtime( get_stylesheet_directory() . '/assets/js/layout/slider-brand.js' ), true );
    wp_register_script( 'slider-download', get_stylesheet_directory_uri() . '/assets/js/layout/slider-download.js', array( 'slick' ), filemtime( get_stylesheet_directory() . '/assets/js/layout/slider-download.js' ), true );
    wp_register_script( 'slider-download-schmal', get_stylesheet_directory_uri() . '/assets/js/layout/slider-download-schmal.js', array( 'slick' ), filemtime( get_stylesheet_directory() . '/assets/js/layout/slider-download-schmal.js' ), true );
    wp_register_script( 'pardot-form', get_stylesheet_directory_uri() . '/assets/js/layout/pardot-form.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/layout/pardot-form.js' ), true );
    wp_register_script( 'tagesseminare_more_reviews', get_stylesheet_directory_uri() . '/assets/js/tagesseminare-more-reviews.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/tagesseminare-more-reviews.js' ), true );
    wp_register_script( 'list-karriere-veranstaltungen', get_stylesheet_directory_uri() . '/assets/js/list-karriere-veranstaltungen.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/list-karriere-veranstaltungen.js' ), true );
    wp_register_script( 'header-contact', get_stylesheet_directory_uri() . '/assets/js/module/header-contact.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/module/header-contact.js' ), true );
    wp_register_script( 'mouseflow', get_stylesheet_directory_uri() . '/assets/js/mouseflow.js', array(), '1.0', false );
}

add_action('wp_enqueue_scripts', 'msq_include_scripts');

function msq_enqueue_pardot_resizing( $regex ) {
	if( !wp_script_is( 'pardot-resizing', 'registered' ) ) {
		msq_include_scripts();
	}
	wp_enqueue_script( 'pardot-resizing' );
	return $regex;
}
add_filter( 'pardot_https_regex', 'msq_enqueue_pardot_resizing' );

function enqueue_mouseflow_tracking_for_tagesseminar_page()
{
    /* Mouseflow für die Event-Seiten*/
    if ( preg_match( '/events?|freelancer/i', $_SERVER['REQUEST_URI'] ) ) {
        wp_enqueue_script( 'mouseflow' );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_mouseflow_tracking_for_tagesseminar_page');



function enqueue_smallchat_on_career() {

    if ( get_field('chat-online', 'option') == 'online') {
        $page_array = array( 'it-karriere', 'aufsteigerprogramm-professional-it-consultant' );
        if ( is_page( $page_array ) || preg_match( '/stellenangebote/i', $_SERVER['REQUEST_URI'] ) || preg_match( '/karriere/i', $_SERVER['REQUEST_URI'] ) ) {
            wp_register_script( 'smallchat', 'https://embed.small.chat/T0397D64CG6K9M3RGF.js' );
            wp_enqueue_script( 'smallchat' );
            wp_register_script( 'smallchat_delete_automessage', get_stylesheet_directory_uri() . '/assets/js/smallchat-delete-automessage.js', array( 'smallchat' ) );
            wp_enqueue_script( 'smallchat_delete_automessage' );
        }
    }

}
add_action( 'wp_enqueue_scripts', 'enqueue_smallchat_on_career' );


/*
 * Diese Funktion sorgt dafür, dass der Cache geleert wird, sobald der Chat online oder offline gesetzt wird.
 * Der Chat wird nämlich nur angezeigt, wenn die Option "chat-online" auf "online" gestellt ist. @see function enqueue_smallchat_on_career()
 */
function msq_acf_save_acf_options_chat( $post_id ) {

    if ( $post_id == 'options' && $_GET["page"] == 'acf-options-chat' ) {
        require( get_home_path() . 'wp-load.php' );
        if ( function_exists( 'rocket_clean_domain' ) ) {
            rocket_clean_domain();
        }
    }

}
add_action('acf/save_post', 'msq_acf_save_acf_options_chat', 20);

function msq_register_image_size(){
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'posts_slider', 476, 317, true );
    add_image_size( 'solution-thumb_small', 300, 200, true );
    add_image_size( 'msq_schulungen', 720, 288, true );
    add_image_size( 'top-downloads', 240, 300, false );
    add_image_size( 'downloads', 200, 450, false );
	add_image_size( 'seminar-thumb', 360, 144, true );
    add_image_size( 'webinar-thumb', 630, 400, false );
	add_image_size( 'downloads-teaser', 150, 150, false );
	add_image_size( 'webinar-archive', 298, 180, false );
	add_image_size( 'download-archive', 80, 139 );
	add_image_size( 'imgdyncon', 240, 270 );
	add_image_size( 'referenten-thumb', 160, 80 );
	add_image_size( 'single-webinar', 560, 365 );
	add_image_size( 'single-expertise', 730, 1000 );
	add_image_size( 'menu-thumb', 180, 203 );
	add_image_size( 'msq-home-portfolio', 175, 175 );
	add_image_size( 'msq-home-knowhow', 210, 128 );
	add_image_size( 'msq-home-downloads', 307, 338 );
	add_image_size( 'msq-customer-thumb', 180, 180 );
	add_image_size( 'single-expertise-cat-conversions',460, 300 );
	add_image_size( 'blog-category-more', 360, 406 );
	add_image_size( 'blog-post-equal', 240, 240, true );
	add_image_size( 'blog-post-wide', 570, 321 );
	add_image_size( 'webinar-single-wide', 652.5 );
	add_image_size( 'webinar-kategorien', 360, 144, false );
	add_image_size( 'msq-post-related', 500, 282, array('center', 'top') );
	add_image_size('msq_schulungen_slider', 238, 133);
	add_image_size('msq_schulungen_kat', 438, 175);
	add_image_size('msq-schulungskategorie', 361, 175, true);
}
add_action( 'after_setup_theme', 'msq_register_image_size' );

//* Add new image sizes to post or page editor
add_filter( 'image_size_names_choose', 'mytheme_image_sizes' );
function mytheme_image_sizes( $sizes ) {

    $mythemesizes = array(
		'single-expertise' 		=> __( 'Expertise-Size' ),
		'single-webinar'		=> __( 'Webinar-Size' ),
		'msq-customer-thumb'	=> __( 'weitere Referenzen' )
    );
    $sizes = array_merge( $sizes, $mythemesizes );

    return $sizes;
}
require_once('inc/custom_metabox/include_metabox_for_employment_page.php');

/**
 * Enable unfiltered_html capability
 *
 * @param  array  $caps    The user's capabilities.
 * @param  string $cap     Capability name.
 * @param  int    $user_id The user ID.
 * @return array  $caps    The user's capabilities, with 'unfiltered_html' added.
 */
function msq_filter_unfiltered_html( $caps, $cap, $user_id ) {
	if( 'unfiltered_html' === $cap ) {
		$caps = array( 'unfiltered_html' );
	}
	return $caps;
}
add_filter( 'map_meta_cap', 'msq_filter_unfiltered_html', 1, 3 );


/**
 * Setze die Anzahl der Revisionen auf 5
 */
function msq_revisions( $num, $post ) {
    $num = 5;
    return $num;
}
add_filter( 'wp_revisions_to_keep', 'msq_revisions', 10, 2 );

/**
 * Diese Funktion wird von Facebook als Redirect-URL verwendet um
 * einen neuen access_token zu speichern.
 */
function msq_ajax_update_facebook_api_access_token() {
	// Sind alle Informationen vorhanden?
	if( !empty( $_GET[ 'code' ] ) && !empty( $_GET[ 'state' ] ) ) {
		$stateKey = get_option( 'facebook_api_state_key' );
		// Wurde die Anfrage von wp-cli gestellt?
		if( $_GET[ 'state' ] == $stateKey ) {
			// Lösche den unique state_key
			update_option( 'facebook_api_state_key', '' );
			// Lade alle benötigten Informationen für eine API Anfrgae
			$appId = get_field( 'facebook_api_app_id', 'options' );
			$appSecret = get_field( 'facebook_api_app_secret', 'options' );
			$ajaxUrl = add_query_arg( array(
				'action' => 'msq_ajax_update_facebook_api_access_token'
			), admin_url( 'admin-ajax.php' ) );
			// Erstelle eine Anfrage für einen neuen Access-Token
			$response = wp_remote_get( add_query_arg( array(
				'client_id' => $appId,
				'client_secret' => $appSecret,
				'redirect_uri' => $ajaxUrl,
				'code' => $_GET[ 'code' ]
			), 'https://graph.facebook.com/oauth/access_token' ), array(
				'timeout' => 50
			));
			if( !empty( $response[ 'body' ] ) ) {
				$jsonResponse = json_decode( $response[ 'body' ] );
				// Wurde ein Access-Token mit gesendet?
				if( !empty( $jsonResponse->access_token ) ) {
					// Speichere den Access-Token ab
					update_field( 'facebook_api_access_token', $jsonResponse->access_token, 'options' );
					// Verlängere den Access_Token
					$appSecretProof = hash_hmac( 'sha256', $jsonResponse->access_token, $appSecret );
					$response = wp_remote_get( add_query_arg( array(
						'grant_type' => 'fb_exchange_token',
						'client_id' => $appId,
						'client_secret' => $appSecret,
						'fb_exchange_token' => $jsonResponse->access_token,
						'appsecret_proof' => $appSecretProof
					), 'https://graph.facebook.com/oauth/access_token' ), array(
						'timeout' => 50
					));
					if( !empty( $response[ 'body' ] ) ) {
						$jsonResponse = json_decode( $response[ 'body' ] );
						// Wurde ein Long-Live-Access-Token mit gesendet?
						if( !empty( $jsonResponse->access_token ) ) {
							// Speichere den Long-Live-Access-Token ab
							update_field( 'facebook_api_access_token', $jsonResponse->access_token, 'options' );
						}
					}
				}
			}
		}
	}
	// Zur Startseite weiterleiten
	wp_redirect( home_url() );
	wp_die();
}
add_action( 'wp_ajax_msq_ajax_update_facebook_api_access_token', 'msq_ajax_update_facebook_api_access_token' );

/**
 * Diese Funktion schränkt den Zugang zu den ACF-Einstellungen ein.
 * @see wp-content\themes\ms_basis_theme\functions.php
 */
function msq_filter_acf_capability( $show ) {
	return 'manage_network';
}
add_filter( 'acf/settings/capability', 'msq_filter_acf_capability' );

/**
 * Diese Funktion fügt für das verändern des Post-Status von
 * Veröffentlicht zu !Veröffentlicht.
 * Sollte kein 'administrator' Cap vorhanden sein, so wird eine E-Mail an
 * onlinemarketing@mindsquare.de gesendet.
 * @see wp-content\themes\ms_basis_theme\functions.php
 *
 * @param string $new_status
 * @param string $old_status
 * @param WP_Post $post
 */
function msq_action_cap_transition_post_status( $new_status, $old_status, $post ) {
	// Prüfe den neuen Status
	if( $old_status == 'publish' && $new_status != 'publish' ) {
		if( !current_user_can( 'administrator' ) ) {
			$msg = 'Der Post ' . $post->post_title . ' mit dem Link ' . get_permalink( $post ) . ' und der ID ' . $post->ID . ' ist nicht mehr öffnetlich zugänglich!';
			$subject = 'Der Post ' . $post->post_title . ' ist nicht mehr öffentlich zugänglich';
			if( !wp_mail( 'onlinemarketing@mindsquare.de', $subject, $msg, array( 'Content-Type: text/html; charset=UTF-8' ) ) ) {
				trigger_error( 'Eine E-Mail an onlinemarketing@mindsquare.de konnte nicht versendet werden. ' . $msg );
			}
		}
	}
}
add_action( 'transition_post_status', 'msq_action_cap_transition_post_status', 10, 3 );

function msq_deactivate_browserconfig () {
    echo '<meta name="msapplication-config" content="none" />';
}
add_action( 'wp_head', 'msq_deactivate_browserconfig' );

function msq_filter_deactivate_wpseo_json( $data ) {
	return array();
}
add_filter( 'wpseo_json_ld_output', 'msq_filter_deactivate_wpseo_json', 10, 1 );

// Registrieren der Sidebar für den Blog-Bereich
add_action( 'widgets_init', 'msq_register_sidebars' );
function msq_register_sidebars() {
    register_sidebar(
        array(
            'id'            => 'sidebar-blog',
            'name'          => __( 'Blog-Sidebar' ),
            'description'   => __( 'Diese Sidebar wird auf allen Blogbeiträgen ausgespielt.' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        )
    );

	register_sidebar(
		array(
			'id'            => 'sidebar-knowhow',
			'name'          => __( 'Knowhow-Sidebar' ),
			'description'   => __( 'Diese Sidebar wird auf den Knowhows ausgespielt.' ),
			'before_widget' => '<div class="mb-3">',
			'after_widget'  => '</div>',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}

// Registrieren des Widgets "Downloads"
add_action( 'widgets_init', function() { register_widget( 'Widget_Download' ); } );

function rp_relationship_query( $args, $field, $post_id ) {

	$catargs = array(
		'post_status'	=> 'publish',
		'numberposts' 	=> -1,
		'category__in'	=> wp_get_post_categories( $post_id )
	);
	$posts = get_posts( $catargs );
	$count = count( $posts );
	# sind in der Kategorie weniger als 5 Beitraege, holt er sich alle Beitraege
	if ( $count < 5 ) {
		# Sortiert die Posts nach Relevanz
		$args['order'] = 'DESC';
	}else{
		# Zeigt die Posts aus der gleichen Kategorie
		$args['category__in'] = wp_get_post_categories( $post_id );
	}
	# Zeigt nur Posts an die Veröffentlicht sind
	$args['post_status'] = array('publish');
	# Zeigt den eigenen Beitrag nicht an
	$args['post__not_in'] = array( $post_id );
	$args['meta_query'] = array( array( 'key'=> '_thumbnail_id' ) );
    return $args;
}
add_filter('acf/fields/relationship/query/name=post', 'rp_relationship_query', 10, 3);

/*
*
*
* Verwandte Beitraege
*
*/

function save_related_post_meta( $post_id, $post, $update ) {
	if( $post->post_type == 'post' ) {
		global $wpdb;

		$id 		= get_the_ID();
		$postmetas 	= get_post_meta( $id,'rp_post',$single = false );
		# Daten aus dem ACF-Feld
		$acfposts = get_field( 'appropriate_links');
		/* var_dump( $acfposts ); */
		$postcat 		= wp_get_post_categories( $id );
		$related_posts = [];
		if( !empty( $acfposts ) ) {
			foreach( $acfposts as $acfpost ){
				foreach( $acfpost as $post ){
					foreach( $post as $p ){
						$related_posts[] = $p;
					}
				}
			}
		}
		$size_acfposts 	= sizeof( $related_posts );
		$post_count 	= 4 - $size_acfposts;
		$catargs = array(
			'post_status'	=> 'publish',
			'numberposts' 	=> -1,
			'category__in'	=> wp_get_post_categories( $id )
		);
		$catposts = get_posts( $catargs );
		$count = count( $catposts );

		if ( empty($related_posts) && $count >= 5 ){
			$id = get_the_ID();
			$loop = array(
				'post_type' 		=> 'post',
				'posts_per_page' 	=> 4,
				'post__not_in' 		=>  array( $id ),
				'post_status' 		=> 'publish',
				'orderby' 			=> 'rand',
				'order' 			=> 	'ASC',
				'meta_query' 		=> array( array( 'key'=> '_thumbnail_id' ) ),
				'category__in' 		=> wp_get_post_categories( $id ),
				'fields'			=> 'ids'
			);
			$catposts = get_posts( $catargs );
			$count = count( $catposts );

			if ( empty($related_posts) && $count >= 5 ){
				$id = get_the_ID();
				$loop = array(
					'post_type' 		=> 'post',
					'posts_per_page' 	=> 4,
					'post__not_in' 		=>  array( $id ),
					'post_status' 		=> 'publish',
					'orderby' 			=> 'rand',
					'order' 			=> 	'ASC',
					'meta_query' 		=> array( array( 'key'=> '_thumbnail_id' ) ),
					'category__in' 		=> wp_get_post_categories( $id ),
					'fields'			=> 'ids'
				);
				$loopquery = get_posts( $loop );
				delete_post_meta( $id, 'rp_post' );
				/* $mergedids = array_merge( $acfposts[0]['post'], $loopquery ); */
				foreach( $loopquery as $key => $value ) {
					add_post_meta( $id, 'rp_post', $value );
				}
				update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $loopquery ) );
			}elseif( empty( $related_posts ) && $count < 5 ){
					$loop = array(
						'post_type' 		=> 'post',
						'posts_per_page' 	=> 4,
						'post__not_in' 		=> array( $id ),
						'post_status' 		=> 'publish',
						'orderby' 			=> 'rand',
						'order' 			=> 'ASC',
						'meta_query' 		=> array( array( 'key'=> '_thumbnail_id' ) ),
						'fields'			=> 'ids'
					);
				$loopquery = get_posts( $loop );
				delete_post_meta( $id, 'rp_post' );
				foreach( $loopquery as $key => $value ) {
					add_post_meta( $id, 'rp_post', $value );
				}
				update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $loopquery ) );
			}elseif ( $size_acfposts <= 4 && $count < 5 ) {
				$args = array(
					'post_type' 		=> 'post',
					'posts_per_page' 	=> $post_count,
					'post_status'		=> 'publish',
					'orderby'			=> 'rand',
					'meta_query'		=> array(array('key' => '_thumbnail_id' )),
					'post__not_in'		=>  $related_posts,
					'fields'			=> 'ids'
				);
				$query = get_posts($args);

				$mergedids = array_merge( $related_posts, $query );
				$mergedids = array_map(
					create_function( '$value', 'return (int)$value;' ),
					$mergedids
				);
				$postmetas = get_post_meta($id, 'rp_post', $single = false );
				delete_post_meta( $id, 'rp_post' );
				foreach ( $mergedids as $key => $value ) {
					add_post_meta( $id, 'rp_post', $value );
				}
				update_post_meta( $id, 'post', maybe_serialize( $mergedids ) );
			}elseif ( $size_acfposts < 4  ) {
				foreach( $related_posts as $key => $value ){
					$post_acf_cat_id = wp_get_post_categories( $value );
					foreach( $postcat as $postca ){
						if( !in_array( $postca, $post_acf_cat_id ) ){
							unset( $related_posts[$key] );
						}
					}
				}
				$max = sizeof($related_posts);

				if( $max < 4 ){
					$post_count = 4 - $max;
					# zaehlt die eingestellten verlinkten Beitraege
					$sql = "SELECT  post_id, meta_key, meta_value, COUNT(meta_value) FROM `{$wpdb->prefix}postmeta` 
					LEFT JOIN {$wpdb->prefix}term_relationships ON {$wpdb->prefix}postmeta.meta_value = {$wpdb->prefix}term_relationships.object_id
					LEFT JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id
					LEFT JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id
					WHERE {$wpdb->prefix}terms.term_id = {$catid[0]}
					AND {$wpdb->prefix}postmeta.meta_key = 'rp_post'
					GROUP BY meta_value 
					ORDER BY COUNT(meta_value) ASC";
					$results = $wpdb->get_results( $sql,'ARRAY_A' );

				if( count( $results ) < 4 ){
					array_push($related_posts, $id);
					$args = array(
						'post_type' 		=> 'post',
						'posts_per_page' 	=> $post_count,
						'post_status'		=> 'publish',
						'orderby'			=> 'rand',
						'meta_query'		=> array(array('key' => '_thumbnail_id' )),
						'category__in' 		=> wp_get_post_categories( $id ),
						'post__not_in'		=> $related_posts,
						'fields'			=> 'ids'
					);
					$query = get_posts($args);
					array_pop($related_posts);
					$mergedids = array_merge( $related_posts, $query );
					$postmetas = get_post_meta($id, 'rp_post', $single = false );
					delete_post_meta( $id, 'rp_post' );
					foreach ( $mergedids as $key => $value ) {
						add_post_meta( $id, 'rp_post', $value );
					}
						update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $mergedids ) );
				}
				}else{
					foreach($results as $result){
						$metavalues[] = $result['meta_value'];
					}
					$metavalues = array_map(
						create_function( '$value', 'return (int)$value;' ),
						$metavalues
					);
					# da nur der erste Beitraeg genoetigt wird, wird sofort danach abgebrochen
					foreach( $metavalues as $metavalue) {
						if (!in_array($metavalue,$related_posts) && $metavalue != $id ) {
							if( count( $related_posts ) )
							$related_posts = $metavalue;
						}
					}
					$postmetas = get_post_meta($id, 'rp_post', $single = false );
					delete_post_meta( $id, 'rp_post' );
					($acfposts);
					foreach ( $related_posts as $key => $value ) {
						add_post_meta( $id, 'rp_post', $value );
					}
					if( array_diff( $related_posts,$postmetas ) ) {
						update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $related_posts ) );
					}
				}
			}else{

				foreach( $related_posts as $key => $value ){
					$post_acf_cat_id = wp_get_post_categories( $value );
					if( $postcat !== $post_acf_cat_id ){
						unset( $related_posts[ $key ] );
					}
				}
				$max = sizeof( $related_posts );
				$post_count = 4 - $max;
				if( $post_count <= 4 && $post_count >= 0 && $count < 4 ) {
					$args = array(
						'post_type'		=> 'post',
						'post_status'	=> 'publish',
						'post_per_page'	=> $post_count,
						'post__not_in'	=> array( $id ),
						'meta_query'	=> array( array( 'key' => '_thumbnail_id' ) ),
						'category__in'	=> wp_get_post_categories($id),
						'fields'		=> 'ids'
					);
					$query = get_posts( $args );
					$mergedids = array_merge( $query, $related_posts );
					$size_mergedids = sizeof( $mergedids );
					$count_diff_mergedids = 4 - $size_mergedids;

					$args = array(
						'post_type'			=> 'post',
						'post_status'		=> 'publish',
						'posts_per_page'	=> $count_diff_mergedids,
						'post__not_in'		=> array( $id ),
						'meta_query'		=> array( array( 'key'	=> '_thumbnail_id' ) ),
						'fields'			=> 'ids'
					);
					$second_query = get_posts( $args );
					$mergedids = array_merge( $mergedids, $second_query );
					delete_post_meta( $id, 'rp_post' );
					foreach( $mergedids as $key => $value ){
						add_post_meta( $id, 'rp_post', $value );
					}
					update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $mergedids ) );
				}elseif( $post_count <= 4 && $post_count > 0 ){
					$args = array(
						'post_type'			=> 'post',
						'post_status'		=> 'publish',
						'posts_per_page'	=> $post_count,
						'post__not_in'		=> array( $id ),
						'meta_query'		=> array( array( 'key' => '_thumbnail_id' ) ),
						'category__in' 		=> wp_get_post_categories( $id ),
						'fields'			=> 'ids'
					);

					$query = get_posts( $args );
					$mergedids = array_merge( $query, $related_posts );
					delete_post_meta( $id, 'rp_post' );
					foreach ( $mergedids as $key => $value ) {
						add_post_meta( $id, 'rp_post', $value );
					}
					update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $mergedids ) );
				}elseif( $post_count < 0 ) {
					$postnotin[] = $id;
					$args = array(
						'post_type'		=> 'post',
						'post_status'	=> 'publish',
						'posts_per_page'	=> 4,
						'post__not_in'		=> $postnotin,
						'meta_query'		=> array( array( 'key' => '_thumbnail_id' ) ),
						'category__in'		=> $postcat,
						'fields'			=> 'ids'
					);

					$query 		= get_posts( $args );
					$mergedids 	= array_merge( $query, $acfposts[0]['post'] );
					delete_post_meta( $id, 'rp_post' );
					foreach( $query as $key => $value ){
						add_post_meta( $id, 'rp_post', $value );
					}
					update_post_meta( $id, 'appropriate_links_0_post', maybe_serialize( $query ) );
				}
			}
		}
	}
}
add_action( 'save_post', 'save_related_post_meta', 10, 3 );


/*
* Login Logo
*/

function msq_login_logo() {
	$attachement_header_logo_url = get_theme_mod( 'header_logo' ) ?: get_theme_mod( 'fb_logo' ); ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url(<?php echo $attachement_header_logo_url ?>);
		height: 60px;
		width: 176px;
		background-size: contain;
		background-repeat: no-repeat;
		}
		body.login div#login .message {
				text-align: center;
				margin-right: 1px;
			}
			body.login div#login .SingleSignOn-Button:hover{
				background-position: right bottom;
				color: orange;  
			}
			body.login div#login .SingleSignOn-Button{
				color: #fff;
				background: linear-gradient( to right, orange 50%, white 50% );
				background-size: 200% 100%;
				background-position: left bottom;
				padding: 5px 10px;
				border: 3px solid orange;
				font-size: 18px;
				font-weight: bold;
				letter-spacing: 1px;
				text-transform: uppercase;
				border-radius: 5px;
				display: inline-block;
				text-align: center;
				cursor: pointer;     
				transition: all ease 0.8s;
			}
		
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'msq_login_logo' );

/**
 * Diese Funktion bearbeitet die Breadcrumb von Yoast-Seo.
 * @see wp-content\plugins\wordpress-seo-premium\frontend\class-breadcrumbs.php:429
 *
 * @param mixed[] $crumbs The crumbs array
 * @return mixed[]
 */
function msq_filter_wpseo_breadcrumb_links( $curmbs ) {
	if( is_tax( 'seminarkategorie' ) || is_singular( 'seminare' ) ) {
		// Füge die Archive-Seite von den Schulungen hinzu
		$archive_seminare_page = get_posts([
			'post_type' => 'page',
			'posts_per_page' => 1,
			'nopaging' => true,
			'meta_key' => '_wp_page_template',
			'meta_value' => 'templates/template-seminarseite.php'
		]);
		if( !empty( $archive_seminare_page ) ) {
			$archive_seminare_page = array_pop( $archive_seminare_page );
			array_unshift( $curmbs, [
				'url' => get_permalink( $archive_seminare_page ),
				'text' => 'Schulungen'
			]);
		}
	}
	return $curmbs;
}
add_filter( 'wpseo_breadcrumb_links', 'msq_filter_wpseo_breadcrumb_links' );


/* 
*	Auswahlfelder fuer die OptinMonsteranzeige
*/

# Menüpunkt im Admin Dashboard anlegen
function msq_optin_add_options_page() {
	acf_add_options_sub_page(
		array(
			'page_title'  => 'Globale Einstellungen für OptinMonster',
			'menu_title'  => 'OptinMonster',
			'parent_slug' => 'options-general.php',
			'capability'  => 'manage_options',
		)
	);
}
add_action( 'acf/init', 'msq_optin_add_options_page' );


/* 
	Title der OptinMonster Kampangnen werden in das Auswahlfeld 
	im Backend geladen. 
	@see Einstellungen => OptinMonster
*/

function add_acf_field_choices( $field ) {

    $field['choices'] = array();   
	$args = array(
		'post_type'			=> 'omapi',
		'posts_per_page'	=> -1
	);
	$results = get_posts($args);

	foreach( $results as $result){

		$id = $result->ID;
		$enabled_result = get_post_meta($id, '_omapi_enabled',false);

		if( !empty($enabled_result) && $enabled_result[0] === "1" ){
			$title = $result->post_title;
			$arr_titles[] = $title;
		}
			
	}
    
	$arr_titles = array_map('trim', $arr_titles);

    if( is_array($arr_titles) ) {

        foreach( $arr_titles as $arr_title ) {          
            $field['choices'][ $arr_title ] = $arr_title;            
		}  
		 
    }
	
	return $field;   
}

add_filter('acf/load_field/name=kampagne', 'add_acf_field_choices');


function msq_add_optin_final_output_filter( $init, $post_id ){

	$optin_kampagnen = get_field( 'optinmonster_kampagnen', 'option' ); // eigene OptinMonster Einstellungen
	$page_template = get_page_template_slug( $post_id ); // Template URL der Seite auf der man ist
	$categories = get_the_category_list( $post_id ); // Kategorien eines Beitrages

	// Prüft ob die Seite eine Karriere Seite ist
	if( strpos( $page_template, 'karriere' ) !== false || strpos( $page_template, 'career' ) !== false || strpos( $page_template, 'employment-notice' ) !== false || strpos($categories, 'karriere') || strpos( $categories, 'career' ) ):
		$career = true;
	else:
		$career = false;
	endif;

	if( !empty( $init ) ):	
		foreach( $init as $key => $value ):		 	
			/* Da $init/$newinit aus dem post_name und dem post_content besteht, 
			muss hier nochmal der einzelne Post anhand des Post Name geholt werden */
			$optin = get_posts( [ 'name' => $key, 'post_type' => 'omapi' ] );
			$show_optin = get_post_meta($optin[0]->ID, '_omapi_show', true);
			$only_optin = get_post_meta($optin[0]->ID, '_omapi_only', false);
			$tax_optin = get_post_meta($optin[0]->ID, '_omapi_taxonomies', true);
			$cat_optin = get_post_meta($optin[0]->ID, '_omapi_categories', true);
			/* sind in den eingenen Einstellungen keine Kampagnen angegeben und keine Einstellungen in den Output Settings getroffen,
			werden die Optins ausgegeben */	
			if( empty( $show_optin ) && ( empty( $only_optin ) || $only_optin[0][0] == "" ) && empty( $tax_optin ) && empty( $cat_optin ) ):
				if( !empty( $optin_kampagnen ) ):
					foreach( $optin_kampagnen as $optin_kampagne ):
						if( $optin_kampagne['kampagne'] == $optin[0]->post_title ):						
							if( $optin_kampagne['output_location'] == 'optin_career' ):
								if( !$career ):
									unset( $init[$key] );
								endif;						
							else:
								if($career):
									unset( $init[$key] );
								endif;
							endif;						
						endif;
					endforeach;
				endif;
			endif;
		endforeach;
	endif;	
	return $init;
}
add_filter( 'optin_monster_api_final_output', 'msq_add_optin_final_output_filter', 10, 2 );



function msq_google_option_page() {
	acf_add_options_sub_page(
		array(
			'page_title'  => 'Globale Einstellungen für Google Analytics Rest API',
			'menu_title'  => 'Google Analytics API',
			'parent_slug' => 'options-general.php',
			'capability'  => 'manage_options',
		)
	);
}
add_action( 'acf/init', 'msq_google_option_page' );

function msq_update_indexing_api( $new_status, $old_status, $post ){
	$projectID = get_field('project_id', 'options');
	$privateKeyID = get_field('private_key_id', 'options');
	$privateKey = get_field('private_key', 'options');
	$privateKey = str_replace('\n', "\n", $privateKey);
	$clientEmail = get_field('client_email', 'options');
	$clientID = get_field('client_id', 'options');
	$clientCertUrl = get_field('client_cert_url', 'options');
	$key = array(
		"type" => "service_account",
		"project_id" => $projectID,
		"private_key_id" => $privateKeyID,
		"private_key" => $privateKey,
		"client_email" => $clientEmail,
		"client_id" => $clientID,
		"auth_uri" => "https://accounts.google.com/o/oauth2/auth",
		"token_uri" => "https://oauth2.googleapis.com/token",
		"auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
		"client_x509_cert_url" => $clientCertUrl
	);
	$KEY_FILE_LOCATION = $key;

	if( ( $new_status == 'publish' ) && ( $old_status == 'publish' ) && $post->post_type == 'employment_notice' ):
		require_once 'inc/google_api/vendor/autoload.php';
		$client = new Google_Client();
		$client->setAuthConfig( $KEY_FILE_LOCATION );
		$client->addScope('https://www.googleapis.com/auth/indexing');

		// Get a Guzzle HTTP Client
		$httpClient = $client->authorize();
		$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

		$url = get_permalink( $post );

		// Define contents here. The structure of the content is described in the next step.
		$content = '{
			"url": "'. $url . '",
			"type": "URL_UPDATED"
		}';
		
		$httpClient->post($endpoint, [ 'body' => $content ]);
	elseif( ( $new_status == 'publish' ) && ( $old_status != 'publish' ) && $post->post_type == 'employment_notice'  ):
		require_once 'inc/google_api/vendor/autoload.php';
		$client = new Google_Client();
		$client->setAuthConfig( $KEY_FILE_LOCATION );
		$client->addScope('https://www.googleapis.com/auth/indexing');

		// Get a Guzzle HTTP Client
		$httpClient = $client->authorize();
		$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

		$url = get_permalink( $post );

		// Define contents here. The structure of the content is described in the next step.
		$content = '{
			"url": "'. $url . '",
			"type": "URL_UPDATED"
		}';
		
		$httpClient->post($endpoint, [ 'body' => $content ]);
	elseif(  ( $new_status != 'publish' ) && ( $old_status === 'publish' ) && $post->post_type == 'employment_notice' ):
		require_once 'inc/google_api/vendor/autoload.php';
		$client = new Google_Client();
		$client->setAuthConfig( $KEY_FILE_LOCATION );
		$client->addScope('https://www.googleapis.com/auth/indexing');

		// Get a Guzzle HTTP Client
		$httpClient = $client->authorize();
		$endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

		$url = get_permalink( $post );

		// Define contents here. The structure of the content is described in the next step.
		$content = '{
			"url": "'. $url . '",
			"type": "URL_DELETED"
		}';
		
		$httpClient->post($endpoint, [ 'body' => $content ]);
	endif;
}
add_action( 'transition_post_status', 'msq_update_indexing_api', 9, 3 );


function msq_exclude_empty_webinar_categories($terms){

	$webinare_term_list = get_terms( 'unternehmens-kategorie' );
	foreach( $webinare_term_list as $webinar_term ) {
		$webinar_list = get_posts( array( 
			'post_type'     => 'webinare',
			'numberposts'   => -1,
			'tax_query'     => array( 
				array(
				'taxonomy'  => 'unternehmens-kategorie',
				'field'     => 'term_id',
				'terms'     => $webinar_term->term_id
			 ))
		 ) );
		 if(empty( $webinar_list )){
			$terms[] = $webinar_term->term_id; 
		 }
	}
	return $terms;

}
add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', 'msq_exclude_empty_webinar_categories', 10, 1 );
