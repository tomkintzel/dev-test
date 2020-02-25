<?php

class MSQ_Structured_Data_Item_List extends MSQ_Structured_Data_Thing {
	private $itemList;
	private $itemCount;

	/**
	 * MSQ_Structured_Data_Item_List constructor.
	 *
	 * @param array|null $items Die Listenelemente
	 * @param int|null $itemCount Die Anzahl an Listenelementen
	 */
	public function __construct( array $items = null, $itemCount = null ) {
		$this->itemList = [];
		$this->itemCount = $itemCount;

		if ( !empty( $items ) ) {
			$this->addItems( $items );
		}

		parent::__construct();
	}

	/**
	 * Erzeugt eine ItemList aus einem Array.
	 *
	 * @see MSQ_Structured_Data_Item_List::itemsFromArray
	 * @return MSQ_Structured_Data_Item_List
	 */
	public static function fromArray( array $array, $urlKey = 'href', $useIndexAsPosition = true, $positionKey = 'position' ) {
		$item = MSQ_Structured_Data_Item_List::itemsFromArray( $array, $urlKey, $useIndexAsPosition, $positionKey );
		return new MSQ_Structured_Data_Item_List( $item, count( $item ) );
	}

	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'ItemList',
			'itemListElement' => $this->itemList
		);

		if ( !empty( $this->itemCount ) ) {
			$this->structuredData['numberOfItems'] = $this->itemCount;
		}
	}

	/**
	 * Fügt der ItemList ein Element hinzu.
	 *
	 * @param int $position Die Position des Elements. Keine Duplikate erlaubt.
	 * @param string $url Die URL des Elements.
	 */
	public function addItem( $position, $url ) {
		$this->itemList[] = [
			'@context' => 'http://schema.org',
			'@type' => 'ListItem',
			'position' => $position,
			'url' => $url
		];
	}

	/**
	 * Fügt der ItemList einen Post hinzu.
	 *
	 * @param int $position Die Position des Posts. Keine Duplikate erlaubt.
	 * @param WP_Post $post Der Post, welcher hinzugefügt werden soll
	 */
	public function addPost( $position, WP_Post $post ) {
		$url = get_permalink( $post );
		$this->addItem( $position, $url );
	}

	public function addItems( $items ) {
		for ( $i = 0; $i < count( $items ); $i++ ) {
			if ( $items[$i] instanceof WP_Post || (
					is_object( $items[$i] ) &&
					property_exists( $items[$i], 'ID' ) &&
					property_exists( $items[$i], 'post_type' )
				)
			) {
				$this->addPost( $i + 1, $items[$i] );
			} else {
				$this->addItem( $items[$i]['position'], $items[$i]['url'] );
			}
		}
	}

	/**
	 * Erzeugt aus einem Array heraus Listenelemente.
	 *
	 * @param array  $array Das Array, aus welchem die Listenelemente erzeugt werden sollen
	 * @param string $urlKey Der Schlüssel, mit welchem die URL im Array hinterlegt ist
	 * @param bool   $useIndexAsPosition Ob der Index eines Elements seiner Position entspricht
	 * @param string $positionKey Falls obiges nicht zutrifft, wird anhand dieses Schlüssels die Position abgefragt
	 *
	 * @return array Die erzeugten Listenelemente in einem Array, mit ihrer Position als Index
	 */
	public static function itemsFromArray( array $array, $urlKey = 'href', $useIndexAsPosition = true, $positionKey = 'position' ) {
		$items = [];
		$index = 1;
		foreach ( $array as $item ) {
			if ( $useIndexAsPosition ) {
				$position = $index++;
			} else {
				$position = $item[$positionKey];
			}

			$url = $item[$urlKey];

			$items[] = [
				'position' => $position,
				'url' => $url
			];
		}

		return $items;
	}
}

?>
