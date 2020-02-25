<?php
require_once __DIR__ . '/WebPage.php';
class MSQ_Structured_Data_Contact_Page extends MSQ_Structured_Data_Web_Page {
	protected function defineStructure() {
		parent::defineStructure();
		$this->structuredData['@type'] = 'ContactPage';
	}
}
?>
