<?php 
setlocale( LC_ALL, 'de_DE@euro');

get_header();

?>
<div id="page-container">
	<main id="tagesseminare" class="page-<?php the_ID(); ?>">
		<?php 
		$option = 'option';
		// Bei den Events wird ts (Tagesseminar) vorangestellt, da Options in der DB keiner POST-ID zugeordnet sind.
		$prepend = 'ts_';
		require( dirname( __FILE__ ) . '/inc/templates/page_builder_v2_layouts.php' ); 

		$header_content  = '<h1 class="text-center mb-4">mindsquare Events</h1>';
		$header_content .= !empty( $content ) ? $content : '';
		?>
		<header>

			<?php
			$default_background = array(
				'background' => null,
				'bg_color' => null,
				'text_color' => null,
				'transparent_background' => null,
				'image' => null,
				'image_size' => null,
				'parallax' => null
			);
			$config_background = wp_parse_args( get_field( 'ts_config-bg', 'option' ), $default_background );
			$config_background_mobile = $config_background[ 'mobile' ] ? $config_background[ 'mobile_section' ] : $default_background + array(
				'mobile' => false,
				'mobile_size' => null
			);
			$config_css = get_field( 'ts_config-css', 'option' );
			msq_add_section( array(
				'background'				=> $config_background[ 'background' ],
				'bg_color'					=> $config_background[ 'bg_color' ],
				'text_color'				=> $config_background[ 'text_color' ],
				'transparent_background'		=> $config_background[ 'transparent_background' ],
				'background_image'			=> $config_background[ 'image' ],
				'image_size'				=> $config_background[ 'image_size' ],
				'parallax'					=> $config_background[ 'parallax' ],
				'mobile'					=> $config_background[ 'mobile' ],
				'mobile_size'				=> $config_background_mobile[ 'mobile_size' ],
				'mobile_background'			=> $config_background_mobile[ 'background' ],
				'mobile_bg_color'				=> $config_background_mobile[ 'bg_color' ],
				'mobile_text_color'			=> $config_background_mobile[ 'text_color' ],
				'mobile_transparent_background'	=> $config_background_mobile[ 'transparent_background' ],
				'mobile_background_image'		=> $config_background_mobile[ 'image' ],
				'mobile_image_size'			=> $config_background_mobile[ 'image_size' ],
				'image_over_headline'			=> get_field( 'ts_image-over-headline', 'option' ),
				'content'					=> $header_content,
				'classes'					=> get_field( 'ts_padding-y', 'option' ) . ' ' . $config_css[ 'config-css' ][ 'class' ],
				'ids'						=> $config_css[ 'config-css' ][ 'id' ]
				)
			);

			?>
		</header>

		<?php
		$content = '';

		// Nächstes Event in Groß darstellen
		if ( have_posts() ) :
			$next_seminar[ 'datetimestamp' ] = mktime( 0, 0, 0, 12, 31, 2036 );
			while ( have_posts() ) : the_post();
				$events = get_field( 'events' );
				foreach ( $events as $key => $event ) {
					$date_array = explode( ".", $event[ 'date' ] );
					$timestamp = mktime( 0, 0, 0, $date_array[1], $date_array[0], $date_array[2] );
					if ( $next_seminar[ 'datetimestamp' ] > $timestamp && $timestamp > time() ) {
						$next_seminar = array(
							'datetimestamp'	=> $timestamp,
							'date'		=> $event[ 'date' ],
							'title'		=> get_the_title(),
							'description'	=> get_field( 'description_overview' ),
							'location'		=> $event[ 'location' ],
							'href'		=> get_the_permalink(),
							'link_cp'		=> get_field( 'link_register' ),
							'availability'	=> $event[ 'availability' ][ 'value' ],
							'video'		=> get_field( 'video_top' )
						);
					}
				}
			endwhile;
		endif;
		rewind_posts();

		if ( !empty( $next_seminar[ 'date' ] ) ) {
			$content = !empty( $next_seminar[ 'video' ][ 'video' ] ) ? '<div class="date-box-content with-video"><div class="video">' . $next_seminar[ 'video' ][ 'video' ] . '</div>' : '<div class="date-box-content">';
			$content .= '<div class="description"><p><strong>' . $next_seminar[ 'title' ] . '</strong></p><span class="description_overview">' . $next_seminar[ 'description' ] . '</span></div><div class="buttons"><a href="' . $next_seminar[ 'href' ] . '" class="btn btn-primary btn-lg w-100">mehr erfahren<i class="ml-2 fa fa-chevron-right" aria-hidden="true"></i></a><a href="' . get_the_permalink( $next_seminar[ 'link_cp' ]->ID ) . '" class="btn btn-primary btn-lg w-100 mt-2">Zur Anmeldung<i class="ml-2 fa fa-chevron-right" aria-hidden="true"></i></a></div></div>';

			$content = msq_add_module( 'date-box', array(
					'places'		=> 'enough-places',
					'background'	=> 'mind-gray-background mb-5',
					'sub_title'		=> 'Nächster Termin',
					'title'		=> strftime('%A, %d.%m.%Y', $next_seminar[ 'datetimestamp' ] ) . ' in ' . $next_seminar[ 'location' ],
					'content'		=> $content
				),
				true
			); 
		}

		// Alle weiteren aktuellen Events / Vergangenen Events darstellen 
		$future_seminars = msq_add_layout( 'tagesseminar-date-box-list', array(
				'past'		=> false,
				'use_std_query'	=> true
			),
			true
		);

		$past_seminars = msq_add_layout( 'tagesseminar-date-box-list', array(
				'past'		=> true,
				'use_std_query'	=> true
			),
			true
		);

		ob_start(); 

		if ( !empty( $future_seminars ) ) :	?>
			<div class="mind-light-gray-background py-4 px-3 px-lg-0<?php echo !empty( $past_seminars ) ? ' mb-5' : ''; ?>">
				<h3 class="text-center pt-0"><strong>Zukünftige Events:</strong></h3>
				<div class="row justify-content-center mt-4">
					<div class="col-12 col-lg-10 col-xl-8">
						<?php echo $future_seminars; ?>
					</div>
				</div>
			</div>
		<?php endif;

		if ( !empty( $past_seminars ) ) :	?>
			<div class="mind-transparent-background py-4 px-3 px-lg-0">
				<h3 class="text-center pt-0"><strong>Bereits vergangene Events:</strong></h3>
				<div class="row justify-content-center mt-4">
					<div class="col-12 col-lg-10 col-xl-8">
						<?php echo $past_seminars; ?>
					</div>
				</div>
			</div>
		<?php endif; 
		$content .= ob_get_clean();

		msq_add_section( array(
			'background'				=> 'mind-white-background',
			'transparent_background'		=> true,
			'background_image'			=> array( 'url' => get_stylesheet_directory_uri() . '/templates/img/fancy_object_transparent.png' ),
			'content'					=> $content,
			'classes'					=> 'py-5',
			'ids'						=> 'header',
			'image_size'				=> 'horizontal_top',
			)
		);


		// Video zwischen Events und Kundenstimme darstellen
		$video_overview = get_field( 'video_overview', 'option' );
		if ( !empty( $video_overview[ 'video' ] ) ) {
			msq_add_section( array(
				'background'				=> 'mind-yellow-background',
				'headline'					=> $video_overview[ 'title' ] ,
				'content'					=> msq_add_component( 'video', array( 'video' => $video_overview[ 'video' ] ), true ),
				'classes'					=> 'py-5',
				'ids'						=> 'video_overview'
				)
			);
		}

		// Kundenstimmen
		// Die folgende Logik der Kundenstimmen ist auch auf der Event-Einzelseite eingebaut. Falls am folgenden Code Änderungen vorgenommen werden, bitte auch auf 
		// der Event-Einzelseite ändern @see: single-tagesseminare.php
		$reviews = get_field( 'customer_reviews', 'option' ) ?: array();
		foreach ($reviews as $review) {
			global $post;
			$post = $review;
			setup_postdata( $post );
			$reviewer_array[] = array(
				'reviewer'		=> array(
					'image_person'		=> get_field('image_person'),
					'image_company'		=> get_field('image_company'),
					'name'			=> get_field('name'),
					'position'			=> get_field('position'),
					'company'			=> $post->post_title,
					'title'			=> get_field('title'),
					'text'			=> get_field('text', false)
				),
				'video'		=> get_field( 'video' )
			);
			$exclude_posts[] = $post->ID;
		}
		wp_reset_postdata();

		$other_reviews = get_posts( array(
			'posts_per_page' 	=> -1,
			'post_type'		=> 'kundenstimmen',
			'exclude'	=> $exclude_posts
			)
		);

		foreach ( $other_reviews as $review ) {
			global $post;
			$post = $review;
			setup_postdata( $post );
			$reviewer_array[] = array(
				'reviewer'		=> array(
					'image_person'		=> get_field('image_person'),
					'image_company'		=> get_field('image_company'),
					'name'			=> get_field('name'),
					'position'			=> get_field('position'),
					'company'			=> $post->post_title,
					'title'			=> get_field('title'),
					'text'			=> get_field('text', false)
				),
				'video'		=> get_field( 'video' )
			);
		}
		wp_reset_postdata();

		$qty_visible_reviews = count( $reviews ) > 6 ? count( $reviews ) : 6;

		$video = msq_add_component( 'video', array( 'video' => get_field( 'video_reviews_tagesseminar', 'option' ) ), true );
		if ( !empty( $video ) ) {
			$video = '<div class="mt-5"></div>' . $video;
		}

		if ( count( $reviewer_array ) > 0 ) {

			msq_add_section( array(
				'background'				=> 'mind-white-background',
				'parallax'					=> false,
				'classes'					=> 'py-5',
				'ids'						=> 'kundenstimmen',
				'content'					=> $video . '<div class="mt-5"></div>' . 
											msq_add_layout( 'customer-reviews', 
												array(
													'reviews'		=> $reviewer_array,
													'more_reviews'	=> $qty_visible_reviews
												),
												true
											),
				'headline'					=> 'Das sagen bisherige Teilnehmer:'
				)
			);

		}

		?>

	</main>
</div>
<?php

if ( class_exists( 'Msq_Structured_Data' ) ) {
	$description = wp_strip_all_tags( preg_replace( '/<h(\d)[^>]*>[\s\S]*?<\/h\\1[^>]*>/i', '', $header_content ), true );
	new MSQ_Structured_Data_Web_Page([
		'name' => 'mindsquare Events',
		'headline' => 'mindsquare Events',
		'description' => $description,
		'text' => $description
	]);
}

get_footer();
?>
