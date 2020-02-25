<?php


namespace Msq\Schulungskatalog;


use InvalidArgumentException;

/**
 * Eine Klasse, um eine Schriftart für TCPDF darzustellen.
 *
 * @package Msq\Schulungskatalog
 */
class TcpdfFont {
	/** @var string */
	private $name;
	/** @var array */
	private $styles;

	const STYLE_BOLD = 'B';
	const STYLE_ITALICS = 'I';
	const STYLE_UNDERLINE = 'U';
	const STYLE_STRIKETHROUGH = 'D';
	const STYLE_OVERLINE = 'O';

	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @param string $style Schriftstil
	 *
	 * @return string Dateiname des Schriftstils
	 */
	public function GetFilename($style) {
		self::ValidateStyle($style);

		if (isset($this->styles[$style])) {
			$filename = $this->styles[$style];
		} else {
			$filename = $this->styles[''];
		}

		return $filename;
	}

	/**
	 * Setzt den Dateinamen eines Schriftstils
	 *
	 * @param string $style    Schriftstil
	 * @param string $filename Dateiname des Stils
	 */
	public function SetStyle($style, $filename) {
		self::ValidateStyle($style);
		$this->styles[$style] = $filename;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function getStyles() {
		return $this->styles;
	}

	/**
	 * @param array $styles
	 */
	public function setStyles($styles) {
		$this->styles = $styles;
	}

	/**
	 * Überprüft, ob der übergebene Schriftstil gültig ist.
	 *
	 * @param string $style
	 *
	 * @return bool
	 */
	public static function ValidateStyle($style = '') {
		switch ($style) {
			case self::STYLE_BOLD:
			case self::STYLE_ITALICS:
			case self::STYLE_UNDERLINE:
			case self::STYLE_STRIKETHROUGH:
			case self::STYLE_OVERLINE:
			case '':
				return true;
			default:
				throw new InvalidArgumentException(sprintf(
					'"%s" is not a valid TCPDF font style.',
					$style
				));
		}
	}
}
