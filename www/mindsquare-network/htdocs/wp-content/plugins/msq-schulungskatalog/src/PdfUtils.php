<?php


namespace Msq\Schulungskatalog;

/**
 * Generische Hilfsmethoden für PDFs
 * @package Msq\Schulungskatalog
 */
trait PdfUtils {
	public function userUnitsToPx(PDF $pdf, $units, $dpi = 300) {
		return $units * $pdf->getScaleFactor() / $pdf->dpi * $dpi;
	}

	public function MsqGradient(PDF $pdf, $x = 0, $y = 0, $w = 0, $h = 1) {
		$w = $w <= 0 ? $pdf->getPageWidth() : $w;
		$pdf->LinearGradient(
			$x,
			$y,
			$w,
			$h,
			[235, 90, 10],
			[253, 194, 0]
		);
	}

	/**
	 * Gibt die innere Breite einer Seite zurück.
	 * Dafür werden die inneren Abstände von der Breite abgezogen.
	 *
	 * @param PDF    $pdf         Die PDF-Instanz
	 * @param string $pagenum     Die Seitenzahl. Leeren String für die jetzige Seite
	 * @param bool   $leftMargin  Ob der linke Innenabstand abgezogen werden soll
	 * @param bool   $rightMargin Ob der rechte Innenabstand abgezogen werden soll
	 *
	 * @return int Die innere Breite
	 */
	public function getPageInnerWidth(PDF $pdf, $pagenum = '', $leftMargin = true, $rightMargin = true) {
		$margins = $pdf->getMargins();

		$width = $pdf->getPageWidth($pagenum);

		if ($leftMargin) {
			$width -= $margins['left'];
		}

		if ($rightMargin) {
			$width -= $margins['right'];
		}

		return $width;
	}

}
