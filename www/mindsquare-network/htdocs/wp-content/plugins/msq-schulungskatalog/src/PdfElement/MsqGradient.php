<?php


namespace Msq\Schulungskatalog\PdfElement;


use Msq\Schulungskatalog\PDF;

/**
 * Ein Farbverlauf mit den mindsquare-Farben.
 *
 * @package Msq\Schulungskatalog\PdfElement
 */
class MsqGradient {
	protected $x;
	protected $y;
	protected $w;
	protected $h;

	public function __construct($x = 0, $y = 0, $w = 0, $h = 1) {
		$this->x = $x;
		$this->y = $y;
		$this->w = $w;
		$this->h = $h;
	}

	public function output(PDF $pdf) {
		$w = $this->w <= 0 ? $pdf->getPageWidth() : $this->w;
		$pdf->LinearGradient(
			$this->x,
			$this->y,
			$w,
			$this->h,
			[235, 90, 10],
			[253, 194, 0]
		);
	}

	/**
	 * @return int
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * @param int $x
	 */
	public function setX($x) {
		$this->x = $x;
	}

	/**
	 * @return int
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * @param int $y
	 */
	public function setY($y) {
		$this->y = $y;
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
