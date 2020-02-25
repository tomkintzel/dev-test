<?php


namespace Msq\Schulungskatalog\AcfLoader;

/**
 * Ein ACF-Loader für die allgemeinen Einstellungen des Katalogs
 * @package Msq\Schulungskatalog\AcfLoader
 */
class CatalogAcfLoader extends AcfLoader {
	public function __construct($prefix) {
		parent::__construct($prefix, 'options');
	}

	/**
	 * @return mixed|void|null Die URL des Standard-Headerbilds
	 */
	public function getHeaderImage() {
		return $this->getField('header_image');
	}

	/**
	 * @return array Die URLs der Partner-Logos
	 */
	public function getPartnerLogos() {
		$partnerLogos    = $this->getField('partner_logos');
		$partnerLogoUrls = array_column($partnerLogos, 'image');

		return $partnerLogoUrls;
	}

	/**
	 * @return array Der allgemeine Ansprechpartner für den Schulungskatalog.
	 * Ein assoziatives Array in folgender Form:
	 * <pre><code>[
	 *     'image'    => Bild-URL,
	 *     'name'     => Vollständiger Name,
	 *     'position' => Position im Unternehmen,
	 *     'url'      => Website,
	 *     'email'    => E-Mail,
	 *     'tel'      => Telefonnummer
	 * ]
	 * </code></pre>
	 */
	public function getContact() {
		$contact             = [];
		$contact['image']    = $this->getField('contact_image');
		$contact['name']     = $this->getField('contact_name');
		$contact['position'] = $this->getField('contact_position');
		$contact['url']      = $this->getField('contact_url');
		$contact['email']    = $this->getField('contact_email');
		$contact['tel']      = $this->getField('contact_tel');

		return $contact;
	}
}
