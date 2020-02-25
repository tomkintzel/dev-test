<?php


namespace Msq\Schulungskatalog;


use InvalidArgumentException;
use TCPDF;
use TCPDF_FONTS;

/**
 * Verwaltet Schriftarten für TCPDF.
 * @package Msq\Schulungskatalog
 */
class TcpdfFontHandler {
	/** @var string $fontDirectory */
	protected $fontDirectory;

	/** @var TcpdfFont[] */
	protected $fonts;

	/** @var TcpdfFont */
	protected $activeFont;

	/** @var FontPreset[] */
	protected $presets;

	/** @var TCPDF */
	protected $tcpdf;

	public function __construct($fontDirectory, TCPDF $tcpdf) {
		$this->fontDirectory = $fontDirectory;
		$this->tcpdf         = $tcpdf;
	}

	public function AddFont($name, $fontfile, $style = '', $args = []) {
		$args = array_merge([
			'fonttype' => '',
			'enc'      => '',
			'flags'    => 32,
			'platid'   => 3,
			'encid'    => 1,
			'addcbbox' => false,
			'link'     => false
		], $args);

		$filename = TCPDF_FONTS::addTTFfont(
			$this->fontDirectory . '/' . $fontfile,
			$args['fonttype'],
			$args['enc'],
			$args['flags'],
			'',
			$args['platid'],
			$args['encid'],
			$args['addcbbox'],
			$args['link']
		);

		if (isset($this->fonts[$name])) {
			$this->fonts[$name]->SetStyle($style, $filename);
			$font = $this->fonts[$name];
		} else {
			$font = new TcpdfFont($name);
			$font->SetStyle($style, $filename);
			$this->fonts[$name] = $font;
		}

		return $font;
	}

	/**
	 * Setzt eine Schriftart anhand eines {@link TcpdfFont}-Objekts.
	 * Falls ein Parameter nicht übergeben wird, wird der jetzige Wert übernommen.
	 *
	 * @param TcpdfFont|string|null $font Das TcpdfFont-Objekt, dessen Name.
	 * @param string                $style
	 * @param int|null              $size
	 * @param string                $subset
	 * @param bool                  $out
	 */
	public function SetFont($font = null, $style = '', $size = null, $subset = 'default', $out = true) {
		if (is_string($font)) {
			if (isset($this->fonts[$font])) {
				$font = $this->fonts[$font];
			} else {
				throw new InvalidArgumentException(sprintf(
					'The font "%s" has not been registered',
					$font
				));
			}
		} elseif ($font === null) {
			if ($this->activeFont === null) {
				throw new InvalidArgumentException('The font parameter may only be omitted after a font has previously been set.');
			}

			$font = $this->activeFont;
		} elseif (!$font instanceof TcpdfFont) {
			throw new InvalidArgumentException('The font parameter must be an instance of TcpdfFont or a string.');
		}

		$filename = $font->GetFilename($style);
		$this->tcpdf->SetFont(
			$filename,
			$style,
			$size,
			$this->fontDirectory . '/' . $filename . '.php',
			$subset,
			$out
		);
		$this->activeFont = $font;
	}

	public function AddPreset(FontPreset $preset) {
		if ($preset === null) {
			return;
		}

		$this->presets[$preset->GetName()] = $preset;
	}

	/**
	 * @param string $name
	 *
	 * @return FontPreset
	 */
	public function GetPreset($name) {
		if (is_string($name) && isset($this->presets[$name])) {
			return $this->presets[$name];
		} else {
			return null;
		}
	}

	/**
	 * Setzt ein FontPreset anhand des Objekts oder des Namens.
	 *
	 * @param FontPreset|string $preset
	 */
	public function ApplyPreset($preset) {
		if (is_string($preset) && isset($this->presets[$preset])) {
			$preset = $this->presets[$preset];
		} elseif (!$preset instanceof FontPreset) {
			throw new InvalidArgumentException();
		}

		$this->SetFont(
			$preset->GetFont(),
			$preset->GetStyle(),
			$preset->GetSize(),
			$preset->GetSubset(),
			$preset->IsOut()
		);
	}
}
