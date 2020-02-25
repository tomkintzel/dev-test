<?php


namespace Msq\Schulungskatalog;

/**
 * Hilfsmethoden für Schrift.
 *
 * @package Msq\Schulungskatalog
 */
trait FontUtils {
	/**
	 * Zieht die Zeilenhöhe vom Padding ab.
	 *
	 * @param int $padding    Das Padding
	 * @param int $lineHeight Die Zeilenhöhe
	 * @param int $fontSize   Die Schriftgröße
	 *
	 * @return float|int
	 */
	public function adjustPaddingForLineHeight($padding, $lineHeight, $fontSize) {
		return $padding - $this->getLineHeightMarginInUnits($lineHeight, $fontSize);
	}

	/**
	 * Berechnet die Zeilenhöhe in Nutzereinheiten
	 *
	 * @param float $lineHeight Das Zeilenhöhen-Verhältnis (z.B. 1,5)
	 * @param int   $fontSize   Die Schriftgröße
	 *
	 * @return float|int
	 */
	public function getLineHeightMarginInUnits($lineHeight, $fontSize) {
		return $fontSize * ($lineHeight - 1) / 2;
	}
}
