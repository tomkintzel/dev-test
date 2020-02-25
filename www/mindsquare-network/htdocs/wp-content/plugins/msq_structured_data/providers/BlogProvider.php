<?php

class BlogProvider extends DataProvider {
	const PROVIDED_OBJECT = 'blog';

	public function after( WpmPlaceholder &$placeholder, $result ) {
		parent::after( $placeholder, $result );

		restore_current_blog();

		return $result;
	}

	public static function getId() {
		return get_current_blog_id();
	}

	public static function getName( $id = null, $short = true ) {
		self::switchBlog( $id );

		if ( $short ) {
			$url = self::getUrl();

			preg_match( '/^(?:https?:\/\/)?(?:www\.)?([^.]+)(?:\.[\w-]+)+(?:\/.*)?$/', $url, $matches );
			$name = $matches[1];
			$namePattern = '/(' . preg_replace( '/[-_ ]/', '[-_ ]?', $name ) . ')/i';

			$title = wp_title( '', false );

			preg_match( $namePattern, $title, $matches );

			if ( empty ( $matches[1] ) ) {
				$name = ucfirst( str_replace( [ '-', '_' ], '', $name ) );
			} else {
				$name = $matches[1];

			}
		} else {
			$name = get_option( 'blogname' );
		}

		self::resetBlog();

		return $name;
	}

	public static function getDescription( $id = null ) {
		self::switchBlog( $id );
		$description = get_option( 'blogdescription' );
		if( empty( $description ) ) {
			$description = get_option( 'blogname' );
		}
		self::resetBlog();

		return $description;
	}

	public static function getUrl( $id = null ) {
		self::switchBlog( $id );
		$url = get_option( 'home' );
		self::resetBlog();

		return $url;
	}

	public static function switchBlog( $id = null ) {
		if ( is_numeric( $id ) ) switch_to_blog( $id );
	}

	public static function resetBlog() {
		restore_current_blog();
	}

	public static function getTelephone( $id = null ) {
		if( !empty( $id ) && is_numeric( $id ) ) {
			self::switchBlog( $id );
			$kb_theme_options = get_option( 'kb_theme_options' );
			self::resetBlog();
			if( !empty( $kb_theme_options ) && !empty( $kb_theme_options[ 'phonenumber' ] ) ) {
				return $kb_theme_options[ 'phonenumber' ];
			}
		}
		return null;
	}

	public static function getEmail( $id = null ) {
		if( !empty( $id ) && is_numeric( $id ) ) {
			self::switchBlog( $id );
			$kb_theme_options = get_option( 'kb_theme_options' );
			self::resetBlog();
			if( !empty( $kb_theme_options ) && !empty( $kb_theme_options[ 'infomail' ] ) ) {
				return $kb_theme_options[ 'infomail' ];
			}
		}
		return null;
	}

	public static function getDate( $id = null ) {
		if( empty( $id ) || !is_numeric( $id ) ) {
			$id = get_current_blog_id();
		}
		$details = get_blog_details( $id );
		if( !empty( $details ) ) {
			$date = date( DateTime::RFC3339, strtotime( $details->registered ) );
			return $date;
		}
		return null;
	}

	public static function getModified( $id = null ) {
		if( empty( $id ) || !is_numeric( $id ) ) {
			$id = get_current_blog_id();
		}
		$details = get_blog_details( $id );
		if( !empty( $details ) ) {
			$date = date( DateTime::RFC3339, strtotime( $details->last_updated ) );
			return $date;
		}
		return null;
	}
}