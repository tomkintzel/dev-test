<?php


namespace Msq\Schulungskatalog;


use TCPDF;
use function array_intersect_key;
use function call_user_func_array;
use function is_numeric;

/**
 * Eine Erweiterung der TCPDF-Klasse.
 *
 * @package Msq\Schulungskatalog
 */
class PDF extends TCPDF {
	use FontUtils;
	use ImageUtils;

	/** @var TcpdfFontHandler */
	protected $fontHandler;

	/**
	 * Stellt einen kreisförmigen Ausschnitt eines Bildes dar.
	 *
	 * @param string $url  Die URL des Bildes
	 * @param int    $x    Die X-Position des Ausschnitts
	 * @param int    $y    Die Y-Position des Ausschnitts
	 * @param int    $size Die Größe des Ausschnitts
	 */
	public function imageInCircle($url, $x, $y, $size) {
		$introImage = $this->imageFromUrl($url);
		$cropSize   = min(imagesx($introImage), imagesy($introImage));
		$introImage = $this->cropImage($introImage, $cropSize, $cropSize, .5, 0);
		$introImage = $this->imageToString($introImage);

		$imageRadius = $size / 2;

		$this->setCellPaddings(0, 0, 0, 0);

		$this->StartTransform();
		$this->Circle(
			$x + $imageRadius,
			$y + $imageRadius,
			$imageRadius, 0, 360, 'CNZ');

		$this->Image(
			'@' . $introImage,
			$x, $y, $size, $size,
			'', $url, '', true
		);
		$this->StopTransform();
	}

	/**
	 * Erzeugt eine Spalte von Zellen.
	 *
	 * @param int   $width    Die Breite der Spalte
	 * @param int   $height   Die Höhe der Spalte
	 * @param array $paddings Die inneren Abstände der Spalte
	 * @param array $cells    Ein mehrdimensionales-Array von Zellen-Parametern.
	 *                        Folgende Parameter sind valide: 'txt', 'border',
	 *                        'align', 'fill', 'link', 'stretch'.
	 *                        Sie entsprechen den Parametern der {@link Cell()}-Funktion.
	 * @param int   $lastLn   Der Ln-Parameter der letzten Zelle. Siehe {@link Cell()}.
	 *
	 * @see Cell()
	 */
	public function cellColumn($width, $height, $paddings = [], $cells = [], $lastLn = 0) {
		$lastLhPadding    = $this->getLineHeightMarginInUnits($this->getCellHeightRatio(), $this->getFontSize()) / 2;
		$cellCount        = count($cells);
		$singleCellHeight = $height / $cellCount;
		$originalY        = $this->GetY();

		$allowedPaddings = [
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0
		];

		if (is_array($paddings)) {
			$paddings = array_intersect_key($paddings, $allowedPaddings);
			$paddings = array_merge($allowedPaddings, $paddings);
		} elseif (is_numeric($paddings)) {
			$paddings = ['top' => $paddings, 'right' => $paddings, 'bottom' => $paddings, 'left' => $paddings];
		}


		for ($i = 0; $i < $cellCount; $i++) {
			$cell = $cells[$i];

			if (isset($cell['fontPreset'])) {
				$this->fontHandler->ApplyPreset($cell['fontPreset']);
				$lhPadding = $this->getLineHeightMarginInUnits($this->getCellHeightRatio(), $this->getFontSize()) / 2;
			} else {
				$lhPadding = $lastLhPadding;
			}

			$cPaddings = $paddings;

			if ($i === 0) {
				// Erste Zelle
				$ln                  = 2;
				$vAlign              = 'B';
				$cPaddings['top']    = $this->adjustPaddingForLineHeight($paddings['top'], $this->getCellHeightRatio(),
					$this->getFontSize());
				$cPaddings['bottom'] = $lhPadding;
			} elseif ($i === $cellCount - 1) {
				// Letzte Zelle
				$ln                  = $lastLn;
				$vAlign              = 'T';
				$cPaddings['top']    = $lastLhPadding;
				$cPaddings['bottom'] = $this->adjustPaddingForLineHeight($paddings['bottom'],
					$this->getCellHeightRatio(), $this->getFontSize());
			} else {
				// Mittlere Zelle
				$ln                  = 2;
				$vAlign              = 'C';
				$cPaddings['top']    = $lastLhPadding;
				$cPaddings['bottom'] = $lhPadding;
			}

			$defaultUserArgs = [
				'txt'     => '',
				'border'  => 0,
				'align'   => '',
				'fill'    => false,
				'link'    => '',
				'stretch' => 0,
			];

			$args = [
				'width'             => $width,
				'height'            => $singleCellHeight,
				'ln'                => $ln,
				'ignore_min_height' => false,
				'calign'            => 'T',
				'valign'            => $vAlign
			];

			$userArgs = array_intersect_key($cell, $defaultUserArgs);
			$args     = array_merge($args, $defaultUserArgs, $userArgs);

			$this->setCellPaddings($cPaddings['left'], $cPaddings['top'], $cPaddings['right'], $cPaddings['bottom']);
			call_user_func_array([$this, 'Cell'], [
				$args['width'],
				$args['height'],
				$args['txt'],
				$args['border'],
				$args['ln'],
				$args['align'],
				$args['fill'],
				$args['link'],
				$args['stretch'],
				$args['ignore_min_height'],
				$args['calign'],
				$args['valign']
			]);

			$lastLhPadding = $lhPadding;
		}

		if ($lastLn === 0) {
			$this->SetY($originalY, false);
		}
	}

	public function getFontHandler() {
		return $this->fontHandler;
	}
}
