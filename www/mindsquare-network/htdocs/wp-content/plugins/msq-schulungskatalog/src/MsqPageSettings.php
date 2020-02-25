<?php


namespace Msq\Schulungskatalog;


use Msq\Schulungskatalog\Pages\PageSettingsInterface;

/**
 * Umfasst Einstellungen, welche über mehrere Seiten des Schulungskatalogs gleich sind.
 * @package Msq\Schulungskatalog
 */
class MsqPageSettings implements PageSettingsInterface {
	/** @var PDF */
	protected $pdf;

	/** @var TcpdfFontHandler */
	protected $fontHandler;

	/** @var string */
	protected $headerImage;

	/** @var string */
	protected $headerLogo;

	/** @var string[] */
	protected $partnerLogos;

	/** @var array */
	protected $contact;

	/**
	 * MsqPageSettings constructor.
	 *
	 * @param PDF              $pdf
	 * @param TcpdfFontHandler $fontHandler
	 * @param string           $headerImage
	 * @param string           $headerLogo
	 * @param string[]         $partnerLogos
	 */
	public function __construct(
		PDF $pdf,
		TcpdfFontHandler $fontHandler,
		$headerImage,
		$headerLogo,
		array $partnerLogos,
		array $contact
	) {
		$this->pdf          = $pdf;
		$this->fontHandler  = $fontHandler;
		$this->headerImage  = $headerImage;
		$this->headerLogo   = $headerLogo;
		$this->partnerLogos = $partnerLogos;
		$this->contact      = $contact;
	}

	/**
	 * @return PDF
	 */
	public function getPdf() {
		return $this->pdf;
	}

	/**
	 * @param PDF $pdf
	 */
	public function setPdf($pdf) {
		$this->pdf = $pdf;
	}

	/**
	 * @return TcpdfFontHandler
	 */
	public function getFontHandler() {
		return $this->fontHandler;
	}

	/**
	 * @param TcpdfFontHandler $fontHandler
	 */
	public function setFontHandler($fontHandler) {
		$this->fontHandler = $fontHandler;
	}

	/**
	 * @return string
	 */
	public function getHeaderImage() {
		return $this->headerImage;
	}

	/**
	 * @param string $headerImage
	 */
	public function setHeaderImage($headerImage) {
		$this->headerImage = $headerImage;
	}

	/**
	 * @return string
	 */
	public function getHeaderLogo() {
		return $this->headerLogo;
	}

	/**
	 * @param string $headerLogo
	 */
	public function setHeaderLogo($headerLogo) {
		$this->headerLogo = $headerLogo;
	}

	/**
	 * @return string[]
	 */
	public function getPartnerLogos() {
		return $this->partnerLogos;
	}

	/**
	 * @param string[] $partnerLogos
	 */
	public function setPartnerLogos($partnerLogos) {
		$this->partnerLogos = $partnerLogos;
	}

	/**
	 * @return array
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * @param array $contact
	 */
	public function setContact($contact) {
		$this->contact = $contact;
	}
}
