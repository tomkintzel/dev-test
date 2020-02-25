<?php
/**
 *
 */
class MSQ_Header_Walker_Nav_Menu extends Walker_Nav_Menu {
	public static $NAVIGATION_CLASSES = [
		'Navigation-Item',
		'Navigation-MenuItem',
		'Navigation-SubItem'
	];

	private $currentPosition;
	private $multiple_columns;

	/**
	 * @param string $output HTML-Code der ausgespielt wird
	 * @param int $depth Die aktuelle Tiefe
	 * @param mixed $args Weitere Argumente
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if( $depth >= 1 ) {
			$output .= '<ul class="Navigation-SubList">';
		}
	}

	/**
	 * @param string $output HTML-Code der ausgespielt wird
	 * @param int $depth Die aktuelle Tiefe
	 * @param mixed $args Weitere Argumente
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if( $depth >= 1 ) {
			$output .= '</ul>';
		}
	}

	/**
	 * @param string $output HTML-Code der ausgespielt wird
	 * @param mixed $item Der Eintrag
	 * @param int $depth Die aktuelle Tiefe
	 * @param mixed $args Weitere Argumente
	 * @param int $id
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( $depth === 0 ) {
			$this->multiple_columns = get_field('menu_multi_column', $item);

			if ( !isset( $this->multiple_columns ) ) {
				$this->multiple_columns = true;
			}
		}

		$itemClasses = !empty( $item->classes ) ? ( array ) $item->classes : array();

		if( array_key_exists( $depth, self::$NAVIGATION_CLASSES ) ) {
			$navigationClass = self::$NAVIGATION_CLASSES[ $depth ];
		} else {
			$navigationClass = end( self::$NAVIGATION_CLASSES );
		}

		// Dem Navigations-Element die passenden Klassen und IDs geben
		$itemClasses[] = $navigationClass;

		// Falls das Menü die volle Breite hat, die entsprechende Klasse hinzufügen
		if ( $depth === 0 && $this->multiple_columns ) {
			$itemClasses[] = 'Navigation-Item-fullWidth';
		}

		$itemClasses = join( ' ', array_filter( $itemClasses ) );
		$itemClasses = ' class="' . esc_attr( $itemClasses ) . '"';
		$itemId = !empty( $item->ID ) ? ' id="menu-item-' . esc_attr( $item->ID ) . '"' : '';

		// Bearbeite die Attribute für die Links
		$itemAttributes = array_filter( [
			'title' => !empty( $item->attr_title ) ? $item->attr_title : null,
			'target' => !empty( $item->target ) ? $item->target : null,
			'rel' => !empty( $item->xfn ) ? $item->xfn : null,
			'href' => !empty( $item->url ) ? $item->url : null,
		]);
		$itemIsLink =  !empty( $item->url ) && $item->url != 'http://' &&  $item->url != 'https://';
		$itemAttributes = implode(' ', array_map( function($value, $key) {
			return $key . '="' . $value . '"';
		}, $itemAttributes, array_keys( $itemAttributes ) ) );

		// Position in der Navigation
		if( $this->multiple_columns && $depth == 1 ) {
			$position = get_field( 'menu_position', $item );
			if( $position !== $this->currentPosition ) {
				$this->currentPosition = $position;
				$output .= '</ul></div><div class="col-auto col-xl"><ul class="Navigation-MenuList">';
			}
		}

		// Erstelle die Ausgabe
		$output .= '<li' . $itemClasses . $itemId . '>';
		$linkClass = $navigationClass . 'Link';
		if( $itemIsLink ) {
			$output .= $args->before . '<a ' . $itemAttributes . ' class="' . $linkClass . '">' . $args->link_before;
		} else {
			$output .= $args->before . '<div class="' . $linkClass . '">';
		}
		$output .= '<span class="Navigation-Title">' . $item->title . '</span>';
		if( $depth == 0 && !empty( $item->description ) ) {
			$description = apply_filters( 'msq_navigation_description', $item->description, $item );
			$output .= '<span class="Navigation-Description">' . $description . '</span>';
		}
		if( $itemIsLink ) {
			$output .= $args->link_after . '</a>' . $args->after;
		} else {
			$output .= '</div>' . $args->after;
		}

		// Erstelle ein Navigation-Menu
		if( $depth == 0 && !empty( $args->walker->has_children ) ) {
			$output .= '<div class="Navigation-Menu row"><div class="col-auto col-xl"><ul class="Navigation-MenuList">';

			// Begine von Links
			$this->currentPosition = 'left';

			// @todo: Wann wird das gebraucht?
			//$output .= $args->before;
			if( $itemIsLink ) {
				// Setzte einen eigenen Link
				$output .= '<li class="Navigation-MenuItem Navigation-OwnItem">';
				$output .= '<a ' . $itemAttributes . ' class="Navigation-MenuItemLink">' . $args->link_before;
				$output .= '<span class="Navigation-Title">' . $item->title . '</span>';
				$output .= $args->link_after . '</a>';
				$output .= '</li>';
			}
			// @todo: Wann wird das gebraucht?
			//$output .= $args->after;
		}
	}

	/**
	 * @param string $output HTML-Code der ausgespielt wird
	 * @param mixed $item Der Eintrag
	 * @param int $depth Die aktuelle Tiefe
	 * @param mixed $args Weitere Argumente
	 * @param int $id
	 */
	function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if( $depth == 0 && !empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) ) {
			$output .= '</ul></div>';
			$downloadElement = get_field( 'menu_download', $item );
			if( !empty( $downloadElement ) && class_exists( 'MSQ_Download_Embeds' ) ) {
				$embedDownload = MSQ_Download_Embeds::get_embed( $downloadElement, MSQ_Download_Embeds::SMALL, [ 'white' ], ['menu-details-link'], [ 'btn', 'btn-primary', 'btn-small' ] );//download,modifier,additionalclasses,imageclasses,btnclasses
				if( !empty( $embedDownload ) ) {
					$output .= '<div class="col-auto d-none d-xl-block">' . $embedDownload . '</div>';
				}
			}
			$output .= '</div>';
		}
		$output .= '</li>';
	}
}
?>
