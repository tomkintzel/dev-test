<?php
namespace MSQ\Plugin\Quality_Management\Scopes;

class Scope_Builder {
	/** @return Scope[] */
	public static function build( $parent_slug ) {
		$scopes = [];
		$scopes[ 'form' ] = self::add_scope( 'Form', $parent_slug, $parent_slug, 'Pardot-Formulare', 'Pardot-Formulare', 'manage_options' );
		$scopes[ 'email-template' ] = self::add_scope( 'Email_Template', $parent_slug . '-email-template', $parent_slug, 'Pardot-E-Mail-Templates', 'Pardot-E-Mail-Templates', 'manage_options' );
		return $scopes;
	}

	/**
	 * @param string $name
	 * @param string $page_slug
	 * @param string $parent_slug
	 * @param string $page_title
	 * @param string $menu_title
	 * @param string $capability
	 * @return Scope
	 */
	private static function add_scope( $name, $page_slug, $parent_slug, $page_title, $menu_title, $capability ) {
		$column_collection_builder = "MSQ\\Plugin\\Quality_Management\\Scopes\\{$name}\\Column_Collection_Builder";
		$table = "MSQ\\Plugin\\Quality_Management\\Scopes\\{$name}\\Table";
		$scope = null;
		if( class_exists( $column_collection_builder ) && class_exists( $table ) ) {
			$column_collection = $column_collection_builder::build();
			$scope = new Scope( $name, $page_slug, $parent_slug, $page_title, $menu_title, $capability, $column_collection, $table );
		}
		return $scope;
	}
}
