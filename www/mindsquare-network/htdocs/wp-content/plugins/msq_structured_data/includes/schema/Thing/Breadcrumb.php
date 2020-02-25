<?php
class MSQ_Structured_Data_Breadcrumb extends MSQ_Structured_Data_Thing {
	private $itemList;
	private $parameters;

	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = array(
			'@context' => 'http://schema.org',
			'@type' => 'BreadcrumbList'
		);
	}

	public function printStructuredData() {
		if (!isset($this->structuredData['itemListElement'])) {
			$parameters = array(
				'items' => $this->itemList
			);
			if( !empty( $this->parameters ) ) {
				$parameters += $this->parameters;
			}
			$this->structuredData['itemListElement'] = new WpmPlaceholder( 'breadcrumb', [], $parameters );
		}

		parent::printStructuredData();
	}

	public function addItem( $id, $name, $position = null ) {
		if ( empty( $position ) ) {
			$this->itemList[] = [
				'@id' => $id,
				'name' => $name
			];
		} else {
			$this->itemList[$position] = [
				'@id' => $id,
				'name' => $name
			];
		}
	}

	public function addParameter( $name, $value ) {
		$this->parameters[$name] = $value;
	}

	public function getItems() {
		return $this->itemList;
	}


}
?>
