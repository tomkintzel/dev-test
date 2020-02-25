<?php

require_once __DIR__ . '/PostProvider.php';

class BreadCrumbProvider extends DataProvider {
	const PROVIDED_OBJECT = 'breadcrumb';

	/** @var PostProvider */
	private $postProvider;

	public function __construct() {
		$this->postProvider = new PostProvider();
	}

	public function before( WpmPlaceholder &$placeholder ) {
		parent::before( $placeholder );

		$parameters = $placeholder->getParameters();
		$this->postProvider->setPost( $parameters[ 'id' ] );
	}

	public function get( $items = null, $generateBreadcrumb = true ) {
		$generateBreadcrumb = filter_var($generateBreadcrumb, FILTER_VALIDATE_BOOLEAN);
		$result = null;
		$itemList[] = [
			'@id'  => BlogProvider::getUrl(),
			'name' => BlogProvider::getName()
		];

		if ($generateBreadcrumb) {
			if ( is_single() ) {
				$itemList = array_merge( $itemList, $this->getSingleBreadcrumb() );
			} else if ( is_page() && !is_front_page() ) {
				$itemList = array_merge( $itemList, $this->getPageBreadcrumb() );
			} else if( is_category() || is_tax() ) {
				$itemList = array_merge( $itemList, $this->getCategoryBreadcrumb() );
			} else if( is_post_type_archive() ) {
				$itemList = array_merge( $itemList, $this->getArchiveBreadcrumb() );
			} else if( is_author() ) {
				$itemList = array_merge( $itemList, $this->getArchiveAuthorBreadcrumb() );
			} else if( is_date() ) {
				$itemList = array_merge( $itemList, $this->getArchiveDateBreadcrumb() );
			}
		}

		if ( !empty( $items ) ) {
			foreach ( $items as $position => $customItem ) {
				array_splice( $itemList, $position, 0, array( $customItem ) );
			}
		}

		if ( !empty( $itemList ) ) {
			$itemList = array_unique($itemList, SORT_REGULAR);
			$result = [];

			foreach ( $itemList as $item ) {
				if (!empty($item['name']) && !empty($item['@id'])) {
					$result[] = array(
						'@type'    => 'ListItem',
						'position' => count( $result ) + 1,
						'item'     => $item
					);
				}
			}
		}


		return $result;
	}

	protected function getSingleBreadcrumb() {
		if ( is_single( 'post' ) ) {
			$taxonomie = 'Category';
		} else {
			$taxonomies = get_post_taxonomies();
			foreach( $taxonomies as $tmp_taxonomie ) {
				$taxonomie_object = get_taxonomy( $tmp_taxonomie );
				if ( !empty( $tmp_taxonomie ) && $taxonomie_object->public !== false ) {
					$taxonomie = $tmp_taxonomie;
					break;
				}
			}
		}
		if ( !empty( $taxonomie ) ) {
			$term = null;
			if ( class_exists( 'WPSEO_Primary_Term' ) ) {
				$primaryTerm = new WPSEO_Primary_Term( $taxonomie, $this->postProvider->getId() );
				$primaryTerm = $primaryTerm->get_primary_term();
				if ( !empty( $primaryTerm ) ) {
					$term = get_term( $primaryTerm, $taxonomie );
				}
			}
			if ( empty( $term ) ) {
				$terms = get_the_terms( $this->postProvider->getId(), $taxonomie );
				if ( !empty( $terms ) ) {
					$term = reset( $terms );
				}
			}
			if ( !empty( $term ) ) {
				$terms = array( $term );
				while ( $term->parent > 0 ) {
					$term = $term = get_term( $term->parent, $taxonomie );
					array_unshift( $terms, $term );
				}
				foreach ( $terms as $term ) {
					$itemList[] = [
						'@id'  => get_term_link( $term ),
						'name' => html_entity_decode( $term->name, ENT_NOQUOTES, 'UTF-8' )
					];
				}
			}
		} else if ( !is_single( 'post' ) ) {
			$archiveUrl = get_post_type_archive_link( $this->postProvider->getType() );

			if ( !empty( $archiveUrl ) ) {
				$postObject = get_post_type_object( $this->postProvider->getType() );

				$itemList[] = [
					'@id'  => $archiveUrl,
					'name' => $postObject->labels->name
				];
			}
		}

		$itemList[] = [
			'@id'  => $this->postProvider->getUrl(),
			'name' => $this->postProvider->getTitle()
		];

		return $itemList;
	}

	protected function getPageBreadcrumb() {
		$parentPages = array_reverse( get_post_ancestors( $this->postProvider->getPost() ) );

		if( !empty( $parentPages ) ) {
			foreach ( $parentPages as $parentPage ) {
				$this->postProvider->setPost( $parentPage, true );
				$itemList[] = [
					'@id'  => $this->postProvider->getUrl(),
					'name' => $this->postProvider->getTitle()
				];
			}

			$this->postProvider->resetPost();
		}

		$itemList[] = [
			'@id'  => $this->postProvider->getUrl(),
			'name' => $this->postProvider->getTitle()
		];

		return $itemList;
	}

	protected function getCategoryBreadcrumb() {
		$currentTerm = get_queried_object();
		if( empty( $currentTerm ) ) {
			return array();
		}
		$currentTaxonomy = $currentTerm->taxonomy;

		$itemList[] = [
			'@id' => get_term_link( $currentTerm, $currentTaxonomy ),
			'name' => $currentTerm->name
		];
		$parentTermId = $currentTerm->parent;

		while( $parentTermId != 0 ) {
			$parentTerm = get_term( $parentTermId, $currentTaxonomy );
			$itemList[] = [
				'@id' => get_term_link( $parentTerm, $currentTaxonomy ),
				'name' => $parentTerm->name
			];
			$parentTermId = $parentTerm->parent;
		}

		return array_reverse( $itemList );
	}

	protected function getArchiveBreadcrumb() {
		$postType = get_queried_object()->name;
		$archiveLink = get_post_type_archive_link( $postType );
		$archiveTitle = get_the_archive_title();

		$itemList[] = [
			'@id' => $archiveLink,
			'name' => $archiveTitle
		];

		return array_reverse( $itemList );
	}

	protected function getArchiveAuthorBreadcrumb() {
		$itemList = array();
		$authorId = get_the_author_meta( 'ID' );

		if( get_current_blog_id() != 37 ) {
			$team = get_field( 'sd_fb_team_page', 'option' );
			if( !empty( $team ) ) {
				$itemList[] = [
					'@id' => get_permalink( $team ),
					'name' => get_the_title( $team )
				];
			}

			$authordetails = get_posts(array(
				'post_type' => 'authordetails',
				'post_status' => 'publish',
				'meta_key' => 'meta_box_author_id',
				'meta_value' => $authorId
			));
			if( !empty( $authordetails ) ) {
				$authordetails = reset( $authordetails );
				$itemList[] = [
					'@id' => get_permalink( $authordetails ),
					'name' => get_the_title( $authordetails )
				];
			}
		}

		$itemList[] = [
			'@id' => get_author_posts_url( $authorId, get_the_author_meta( 'user_nicename' ) ),
			'name' => 'Beiträge von ' . get_the_author()
		];
		return $itemList;
	}

	protected function getArchiveDateBreadcrumb() {
		global $wp;

		$itemList[] = [
			'@id' => home_url( $wp->request ),
			'name' => '{{archive.title}}'
		];

		return $itemList;
	}
}