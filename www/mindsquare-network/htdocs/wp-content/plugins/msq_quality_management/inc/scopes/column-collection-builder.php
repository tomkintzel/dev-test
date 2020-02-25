<?php
namespace MSQ\Plugin\Quality_Management\Scopes;

/** */
abstract class Column_Collection_Builder {
	abstract public static function build();

	/**
	 * @param string $func_name
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 * @return Closure
	 */
	protected static function get_callback( $func_name, $element_func_names = [], $element_args = [] ) {
		return function( $form ) use( $func_name, $element_func_names, $element_args ) {
			$element = self::get_element( $form, $element_func_names, $element_args );
			if( !empty( $element ) ) {
				return call_user_func( [ $element, $func_name ] );
			} else {
				return '';
			}
		};
	}

	/**
	 * @todo: Sicherung mit is_callable ausbauen, wenn alles getestet wurde
	 *
	 * @param string $text_func_name
	 * @param string $link_func_name
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 * @return Closure
	 */
	protected static function get_link_callback( $text_func_name, $link_func_name, $element_func_names = [], $element_args = [] ) {
		return function( $form ) use( $text_func_name, $link_func_name, $element_func_names, $element_args ) {
			$element = self::get_element( $form, $element_func_names, $element_args );
			if( !empty( $element ) ) {
				$text = call_user_func( [ $element, $text_func_name ] );
				$link = call_user_func( [ $element, $link_func_name ] );
				return sprintf( '<a href="%s">%s</a>', $link, $text );
			} else {
				return '';
			}
		};
	}

	/**
	 * @param string $func_name
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 * @return Closure
	 */
	protected static function get_bool_callback( $func_name, $element_func_names = [], $element_args = [] ) {
		return function( $form ) use( $func_name, $element_func_names, $element_args ) {
			$element = self::get_element( $form, $element_func_names, $element_args );
			if( !empty( $element ) ) {
				return call_user_func( [ $element, $func_name ] ) ? '✅' : '❌';
			} else {
				return '';
			}
		};
	}

	/**
	 * @param string $date_func_name
	 * @param string $user_func_name
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 * @return Closure
	 */
	protected static function get_date_callback( $date_func_name, $user_func_name, $element_func_names = [], $element_args = [] ) {
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		return function( $form ) use( $date_func_name, $user_func_name, $date_format, $time_format, $element_func_names, $element_args ) {
			$element = self::get_element( $form, $element_func_names, $element_args );
			if( !empty( $element ) ) {
				$date = call_user_func( [ $element, $date_func_name ] );
				if( !empty( $date ) ) {
					$result = sprintf( '<abbr title="%s">%s</abbr>', date_i18n( "$date_format $time_format", $date ), date_i18n( $date_format, $date ) );
					if( !empty( $user_func_name ) ) {
						$user = call_user_func( [ $element, $user_func_name ] );
						if( !empty( $user ) ) {
							$result = sprintf( '<a href="%s">%s</a><br />%s', $user->get_read_url(), $user->get_name(), $result );
						}
					}
					return $result;
				}
			}
			return '';
		};
	}

	/**
	 * @param string $func_name
	 * @param string $title
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 * @return Closure
	 */
	protected static function get_modal_callback( $func_name, $title, $element_func_names = [], $element_args = [] ) {
		return function( $form ) use( $func_name, $title, $element_func_names, $element_args ) {
			$element = self::get_element( $form, $element_func_names, $element_args );
			if( !empty( $element ) ) {
				$value = call_user_func( [ $element, $func_name ] );
				if( !empty( $value ) ) {
					$inline_id = sprintf( 'modal-%s', implode( '-', array_merge( [ $form->get_id() ], $element_func_names, [ $func_name ] ) ) );
					$value = htmlspecialchars( $value );
					return sprintf( '<a href="#TB_inline?&width=750&height=700&inlineId=%s" class="thickbox" title="%s">ansehen</a><div id="%s" class="modal fade" style="display: none"><pre>%s</pre></div>', $inline_id, $title, $inline_id, $value );
				}
			}
			return '';
		};
	}

	/**
	 * @param Pardot_Models\Form $form
	 * @param string[] $element_func_names
	 * @param string[] $element_args
	 */
	protected static function get_element( $form, $element_func_names = [], $element_args = [] ) {
		$element = $form;
		foreach( (array) $element_func_names as $args_key => $element_func_name ) {
			if( !empty( $element_args[ $args_key ] ) ) {
				$args = $element_args[ $args_key ];
			} else {
				$args = [];
			}
			$element = call_user_func( [ $element, $element_func_name ], $args );
			if( empty( $element ) ) {
				return null;
			}
		}
		return $element;
	}
}
