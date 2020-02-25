<?php
date_default_timezone_set( 'Europe/Berlin' );

$archive_career_event_title = get_field( 'archive-career-event-title', 'options' );
$archive_career_event_bg_img = get_field( 'archive-career-event-bg-img', 'options' );
$archive_career_event_img = get_field( 'archive-career-event-img', 'options' );
$archive_career_event_text_header = get_field( 'archive-career-event-text-header', 'options' );

$archive_career_event_event_title = get_field( 'archive-career-event-event-title', 'options' );

$archive_career_event_video_title = get_field( 'archive-career-event-video-title', 'options' );
$archive_career_event_video = get_field( 'archive-career-event-video', 'options' );
$archive_career_event_video_btn_text = get_field( 'archive-career-event-video-btn-text', 'option' );
$archive_career_event_video_btn_link = get_field( 'archive-career-event-video-btn-link', 'option' );

$archive_career_event_rating_title = get_field( 'archive-career-event-rating-title', 'options' );

$header_content  = '<h1 class="text-center mb-4">' . $archive_career_event_title . '</h1>';
$header_content .= $archive_career_event_text_header;

$events_raw = get_posts( array(
	'numberposts' => -1,
	'post_type' => 'career-event',
	'post_status' => 'publish'
));
$events = array();

// Die Events filtern und sortieren
if( !empty( $events_raw ) ) {
	foreach( $events_raw as $event ) {
		$event_date = get_field( 'career-event-from-date',  $event, false );
		$event_time_h = get_field( 'career-event-time-h',  $event ) ?: 0;
		$event_time_i = get_field( 'career-event-time-i',  $event ) ?: 0;
		$event_time = strtotime( $event_date . ' ' . $event_time_h . ':' . $event_time_i );
		if( $event_time >= time() ) {
			$events[ $event_time ] = $event;
		}
	}
	ksort( $events );
}

$archive_career_event_rating = get_field( 'archive-career-event-rating', 'option' ) ?: array();
$archive_career_event_rating_qty = !empty( $archive_career_event_rating ) ? min( count( $archive_career_event_rating ), 6 ) : 6;
$archive_career_event_rating_other = get_posts( array(
	'post_type' => 'career-event-rating',
	'post_status' => 'publish',
	'exclude' => array_map( function( $post ) {
		return $post->ID;
	}, $archive_career_event_rating ),
	'posts_per_page' => -1
));
if( !empty( $archive_career_event_rating ) ) {
	$ratings = array_merge( $archive_career_event_rating, $archive_career_event_rating_other );
}
else {
	$ratings = $archive_career_event_rating_other;
}

// Nächsten Events
ob_start(); ?>
<div class="mind-light-gray-background py-4 px-3 px-lg-0">
	<h3 class="text-center pt-0"><strong><?php echo $archive_career_event_event_title; ?></strong></h3>
	<div class="row justify-content-center mt-4">
		<div class="col-12 col-lg-10 col-xl-8">
			<?php msq_add_module( 'list-karriere-veranstaltungen', array(
				'items' => $events
			)); ?>
		</div>
	</div>
</div>
<?php
$events_content = ob_get_clean();

get_header();
?>
<div id="page-container">

	<main id="career-event" class="archive-page">
		<?php msq_add_section( array(
			'background' => 'mind-light-gray-background',
			'background_image' => $archive_career_event_bg_img,
			'transparent_background' => true,
			'image_size' => 'horizontal_center',
			'mobile' => true,
			'mobile_background' => 'mind-light-gray-background',
			'image_over_headline' => $archive_career_event_img,
			'classes' => 'py-5',
			'content' => $header_content
		)); ?>
		<?php if( !empty( $events ) ) {
			msq_add_section( array(
				'background' => 'mind-white-background',
				'classes' => 'py-5',
				'content' => $events_content
			));
		} ?>
		<?php if( !empty( $archive_career_event_video ) ) {
			msq_add_section( array(
				'background' => 'mind-yellow-background',
				'headline' => $archive_career_event_video_title,
				'classes' => 'py-5',
				'ids' => 'video_overview',
				'content' => msq_add_component( 'video', array(
					'video' => $archive_career_event_video
				), true ) . msq_add_layout( 'wrapper', array(
					'class' => 'row justify-content-center mt-5',
					'item-class' => 'col-lg-8 text-center',
					'items' => msq_add_component( 'button', array(
						'link' => $archive_career_event_video_btn_link,
						'text' => $archive_career_event_video_btn_text
					), true )
				), true )
			));
		} ?>
		<?php if( !empty( $ratings ) ) {
			msq_add_section( array(
				'background' => 'mind-white-background',
				'classes' => 'py-5',
				'headline' => $archive_career_event_rating_title,
				'ids' => 'kundenstimmen',
				'content' => msq_add_layout( 'customer-reviews', array(
					'more_reviews' => $archive_career_event_rating_qty,
					'reviews' => msq_add_module( 'acf-repeater', array(
						'items' => $ratings,
						'mapcallback' => function( $item, $key ) {
							return array(
								'reviewer' => array(
									'image_person' => get_field( 'image_person', $item ),
									'image_company' => get_field( 'image_company', $item ),
									'name' => get_field( 'name', $item ),
									'position' => get_field( 'position', $item ),
									'company' => $item->post_title,
									'title' => get_field( 'title', $item ),
									'text' => get_field( 'text', $item, false ),
									'link_story' => ''
								),
								'video' => get_field( 'video', $item )
							);
						}
					))
				), true )
			));
		} ?>
	</main>
</div>
<?php
if( class_exists( 'Msq_Structured_Data' ) ) {
	$description = wp_strip_all_tags( $archive_career_event_text_header, true );
	new MSQ_Structured_Data_Web_Page([
		'name' => $archive_career_event_title,
		'headline' => $archive_career_event_title,
		'description' => $description,
		'text' => $description
	]);
}

get_footer();
?>
