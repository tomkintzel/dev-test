<?php
class MSQ_Structured_Data_Thing extends Msq_Structured_Data {

	private static $createdElements = [];

	public static function isCreated() {
		return isset( self::$createdElements[ get_called_class() ] );
	}

	/**
	 * @return MSQ_Structured_Data_Thing
	 */
	public static function getLastInstance() {
		if (!self::isCreated()) {
			return null;
		}
		return self::$createdElements[get_called_class()];
	}

	/**
	 */
	public function __construct( $values = null ) {
		parent::__construct();
		$this->defineStructure();
		self::$createdElements[ get_called_class() ] = $this;

		if( !empty( $values ) ) {
			foreach( $values as $key => $value ) {
				$this->structuredData[ $key ] = $value;
			}
		}
	}

	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'Thing'
		);
	}
}
?>
