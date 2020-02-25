<?php
class MSQ_Structured_Data_Organization extends MSQ_Structured_Data_Thing {
	/**
	 */
	public $socialLinks = array();

	/**
	 * Diese Funktion definiert für diese Klasse eine neue Struktur.
	 */
	protected function defineStructure() {
		$this->structuredData = OrganizationProvider::get();
		$this->structuredData['sameAs'] = $this->getSocialLinks();
	}

	public function getSocialLinks() {
		return array();
	}

	public function addSocialLink( $url ) {
		$this->socialLinks[] = $url;
	}
}
?>
