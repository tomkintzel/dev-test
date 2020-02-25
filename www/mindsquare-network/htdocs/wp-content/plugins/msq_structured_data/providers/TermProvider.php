<?php

class TermProvider extends DataProvider {
	const PROVIDED_OBJECT = 'term';

	public function get( $id = null, $taxonomy = 'category' ) {
		$termName = null;
		$post = get_post( $id );

		if ( class_exists( 'WPSEO_Primary_Term' ) ) {
			$primaryTerm = new WPSEO_Primary_Term( $taxonomy, $post->ID );
			$primaryTerm = $primaryTerm->get_primary_term();

			if ( !empty( $primaryTerm ) ) {
				$term = get_term( $primaryTerm, $taxonomy );
			}
		}

		if ( empty( $term ) ) {
			$terms = get_the_terms( $post->ID, $taxonomy );

			if ( !empty( $terms ) ) {
				$term = reset( $terms );
			}
		}
		if ( !empty( $term ) ) {
			$termName = $term->name;
		}

		return $termName;
	}

	public function getTitle() {
		$currentTerm = get_queried_object();
		return $currentTerm->name;
	}

	public function getDescription() {
		$currentTerm = get_queried_object();
		return term_description( $currentTerm->term_id );
	}

	public function getUrl() {
		$currentTerm = get_queried_object();
		$currentTaxonomy = $currentTerm->taxonomy;
		return get_term_link( $currentTerm, $currentTaxonomy );
	}
}