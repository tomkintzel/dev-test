<?php


namespace Msq\Schulungskatalog\PdfElement;


use Msq\Schulungskatalog\AssetTrait;
use Msq\Schulungskatalog\ImageUtils;
use Msq\Schulungskatalog\PDF;

/**
 * Eine Bildreihe für die Partnerbilder.
 * @see     ImageUtils::imageRow()
 * @see     ImageUtils::outputImageRows()
 * @package Msq\Schulungskatalog\PdfElement
 */
class PartnerImageRow implements PdfElementInterface {
	use AssetTrait;
	use ImageUtils;

	protected $logos;
	protected $baseX;
	protected $baseY;
	protected $h;
	protected $w;

	/**
	 * PartnerImageRow constructor.
	 *
	 * @param array $logos
	 * @param int   $baseX
	 * @param int   $baseY
	 * @param int   $w
	 */
	public function __construct(array $logos, $baseX, $baseY = 0, $w = 0, $h = 15) {
		$this->logos = $logos;
		$this->baseX = $baseX;
		$this->baseY = $baseY;
		$this->w     = $w;
		$this->h     = $h;
	}

	public function output(PDF $pdf) {
		$rows = $this->imageRow($this->logos, $this->w, $this->h);
		$this->outputImageRows($pdf, $rows, $this->baseX, $this->baseY);;
	}

	/**
	 * @return array
	 */
	public function getLogos() {
		return $this->logos;
	}

	/**
	 * @param array $logos
	 */
	public function setLogos($logos) {
		$this->logos = $logos;
	}

	/**
	 * @return int
	 */
	public function getBaseX() {
		return $this->baseX;
	}

	/**
	 * @param int $baseX
	 */
	public function setBaseX($baseX) {
		$this->baseX = $baseX;
	}

	/**
	 * @return int
	 */
	public function getBaseY() {
		return $this->baseY;
	}

	/**
	 * @param int $baseY
	 */
	public function setBaseY($baseY) {
		$this->baseY = $baseY;
	}

	/**
	 * @return int
	 */
	public function getW() {
		return $this->w;
	}

	/**
	 * @param int $w
	 */
	public function setW($w) {
		$this->w = $w;
	}

	/**
	 * @return int
	 */
	public function getH() {
		return $this->h;
	}

	/**
	 * @param int $h
	 */
	public function setH($h) {
		$this->h = $h;
	}
}
