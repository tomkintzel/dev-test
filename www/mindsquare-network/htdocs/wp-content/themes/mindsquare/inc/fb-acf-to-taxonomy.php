<?php
const EMPLOYMENT_NOTICE_HAS_FB_TAXONOMY_OPTION      = 'employment_notice_has_fb_taxonomy';
const EMPLOYMENT_NOTICE_UPDATE_TAXONOMIES_TRANSIENT = 'employment_notice_update_taxonomies';

function transition_fb_acf_to_taxonomies() {
	if ( ! current_user_can( 'administrator' ) || get_transient( EMPLOYMENT_NOTICE_UPDATE_TAXONOMIES_TRANSIENT ) ) {
		return;
	}

	set_transient( EMPLOYMENT_NOTICE_UPDATE_TAXONOMIES_TRANSIENT, true, 600 );

	$terms    = get_terms( [
		'taxonomy' => 'fachbereiche'
	] );
	$fb_terms = [];

	/** @var WP_Term $term */
	foreach ( $terms as $term ) {
		$fb_terms[ $term->slug ] = $term->term_id;
	}

	$post_args = [
		'post_type'      => 'employment_notice',
		'posts_per_page' => - 1
	];

	$employment_notices = new WP_Query( $post_args );


	/** @var WP_Post $employment_notice */
	foreach ( $employment_notices->posts as $employment_notice ) {
		$term_ids = get_field( 'fachbereiche', $employment_notice ) ?: [];

		$terms_from_acf = [];

		foreach ( $term_ids as $term_id ) {
			$terms_from_acf[] = $fb_terms[ $term_id ];
		}

		$result = wp_set_post_terms( $employment_notice->ID, $terms_from_acf, 'fachbereiche' );

		if ( $result === false ) {
			trigger_error(
				'Konnte die Fachbereiche der Stellenanzeige '
				. $employment_notice->ID . ' nicht von ACF auf die Taxonomie übertragen.'
			);
		} elseif ( $result instanceof WP_Error ) {
			trigger_error( $result->get_error_message() );
		}
	}

	add_option( EMPLOYMENT_NOTICE_HAS_FB_TAXONOMY_OPTION, true );
	delete_transient( EMPLOYMENT_NOTICE_UPDATE_TAXONOMIES_TRANSIENT );
}

if ( ! get_option( EMPLOYMENT_NOTICE_HAS_FB_TAXONOMY_OPTION ) ) {
	add_action( 'wp_footer', 'transition_fb_acf_to_taxonomies' );
}
