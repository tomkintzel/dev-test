<?php

namespace Msq\Schulungskatalog;

use InvalidArgumentException;
use function file_get_contents;
use function getimagesize;
use function imagecreatefromstring;
use function imagecrop;

/**
 * Hilfsmethoden für den Umgang mit Bildern.
 * @package Msq\Schulungskatalog
 */
trait ImageUtils {
	/**
	 * Bequemlichkeits-Methode für {@link file_get_contents()}
	 * und {@link imagecreatefromstring()}.
	 *
	 * @param $url
	 *
	 * @return false|resource
	 */
	public function imageFromUrl($url) {
		$data  = file_get_contents($url);
		$image = imagecreatefromstring($data);

		return $image;
	}

	/**
	 * Ruft je nach $imageType die entsprechende PHP-Funktion auf,
	 * und gibt das übergebene Bild als String zurück.
	 *
	 * @param resource $image     Die Bild-Ressource
	 * @param string   $imageType Der Bild-Typ
	 *
	 * @return false|string
	 */
	public function imageToString($image, $imageType = 'png') {
		ob_start();
		switch ($imageType) {
			case 'jpeg':
			case 'jpg':
				imagejpeg($image);
				break;
			case 'gif':
				imagegif($image);
				break;
			case 'bmp':
				imagebmp($image);
				break;
			case 'png':
			default:
				imagepng($image);
				break;
		}
		$imageString = ob_get_clean();

		return $imageString;
	}

	/**
	 * @param resource $image  Die Bild-Ressource
	 * @param int      $width  Die Breite des Zuschnitts
	 * @param int      $height Die Höhe des Zuschnitts
	 * @param float    $posX   Die X-Position. 0 = Links, 0.5 = Mitte, 1 = Rechts
	 * @param float    $posY   Die Y-Position. 0 = Oben, 0.5 = Mitte, 1 = Unten
	 *
	 * @return bool|resource
	 */
	public function cropImage($image, $width, $height, $posX = .5, $posY = .5) {
		$originalWidth  = imagesx($image);
		$originalHeight = imagesy($image);

		if ($width > $originalWidth
		    || $height > $originalHeight) {
			throw new InvalidArgumentException('Cropped size cannot exceed original size');
		}

		$x = max(0, min($originalWidth, $originalWidth * $posX - $width * .5));
		$y = max(0, min($originalHeight, $originalHeight * $posY - $height * .5));

		return imagecrop($image, [
			'x'      => $x,
			'y'      => $y,
			'width'  => $width,
			'height' => $height
		]);
	}

	/**
	 * Ordnet Bilder in Zeilen aus.
	 * Dabei werden die Bilder auf die Zeilenhöhe herunterskaliert.
	 *
	 * @param array $imageUrls  Die URLs der Bilder
	 * @param int   $rowWidth   Die Breite der Zeilen
	 * @param int   $rowHeight  Die Höhe der Zeilen
	 * @param int   $hRowGutter Der horizontale Abstand zwischen den Bildern
	 * @param int   $vRowGutter Der vertikale Abstand zwischen den Bildern
	 *
	 * @return array Ein assoziatives Array in folgender Form:
	 * <pre><code>[
	 *     0 => [
	 *         'width'  => Breite der ganzen Zeile,
	 *         'images' => [
	 *             'url'    => Bild-URL,
	 *             'width'  => Bild-Breite
	 *             'height' => Bild-Höhe
	 *         ]
	 *     ], ...
	 * ]
	 * </code>
	 * </pre>
	 */
	public function imageRow(
		array $imageUrls,
		$rowWidth,
		$rowHeight = 15,
		$hRowGutter = 5,
		$vRowGutter = 5
	) {
		$lastWidth  = 0;
		$totalWidth = 0;
		$lastIndex  = -1;
		$rowIndex   = 0;
		$rows       = [];

		foreach ($imageUrls as $imageUrl) {
			$imageSize   = getimagesize($imageUrl);
			//avoid division by zero
			if ($imageSize[1] != 0) {
				$scaleFactor = $rowHeight / $imageSize[1];
			} else {
				$scaleFactor = 0;
			}
			$image       = [
				'url'    => $imageUrl,
				'width'  => $imageSize[0] * $scaleFactor,
				'height' => $imageSize[1] * $scaleFactor
			];

			$lastWidth = $totalWidth;

			if ($rowIndex === $lastIndex) {
				$totalWidth += $hRowGutter;
			}
			$totalWidth += $image['width'];
			$lastIndex  = $rowIndex;

			if ($totalWidth > $rowWidth) {
				$rows[$rowIndex]['width'] = $lastWidth;
				$totalWidth               = 0;
				$rowIndex++;
			}

			$rows[$rowIndex]['images'][] = $image;
		}

		$rows[$rowIndex]['width'] = $totalWidth;

		$y = 0;

		$rowCount = count($rows);
		for ($i = 0; $i < $rowCount; $i++) {
			$x = 0;

			$imageCount = count($rows[$i]['images']);
			for ($j = 0; $j < $imageCount; $j++) {
				$rows[$i]['images'][$j]['x'] = $x;
				$rows[$i]['images'][$j]['y'] = $y;

				$x += $rows[$i]['images'][$j]['width'] + $hRowGutter;
			}

			$y += $vRowGutter;
		}

		return $rows;
	}

	/**
	 * Gibt Bildzeilen aus, die beispielsweise mit {@link imageRow()} erzeugt wurden.
	 *
	 * @param PDF   $pdf    Die PDF-Instanz
	 * @param array $rows   Die Bildzeilen. Siehe {@link imageRow()} für das Format.
	 * @param int   $baseX  Anfängliche X-Koordinate
	 * @param int   $baseY  Anfängliche Y-Koordinate
	 * @param float $hAlign Horizontale Ausrichtung der Bilder. 0 = Links, 0.5 = Mittig, 1 = Rechts
	 */
	public function outputImageRows(PDF $pdf, $rows, $baseX, $baseY, $hAlign = .5) {
		foreach ($rows as $row) {
			$x = $baseX - $row['width'] * $hAlign;

			foreach ($row['images'] as $image) {
				$pdf->Image(
					$image['url'],
					$x + $image['x'],
					$baseY + $image['y'],
					$image['width'],
					$image['height']
				);
			}
		}
	}
}
