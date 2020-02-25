<?php


namespace Msq\Schulungskatalog;


use function stristr;

/**
 * Eine Sammlung an Schrift-Einstellungen,
 * welche über den {@link TcpdfFontHandler} angewandt werden können.
 *
 * @package Msq\Schulungskatalog
 */
class FontPreset {
	/** @var string */
	private $name;

	/** @var TcpdfFont * */
	private $font;
	/** @var string */
	private $style;
	/** @var float */
	private $size;
	/** @var string */
	private $fontfile;
	/** @var mixed */
	private $subset;
	/** @var bool */
	private $out;

	/**
	 * Der Konstruktor.
	 *
	 * @param string      $name   Der Name des Presets
	 * @param TcpdfFont   $font   Die Schriftart
	 * @param string|null $style  Der Schriftstil (z.B. 'B' für 'Bold'). Null um vorherige beizubehalten.
	 * @param float|null  $size   Die Schriftgröße. Null um vorherige beizubehalten.
	 * @param string      $subset Irgendein komisches TCPDF-Ding. Kann ignoriert werden.
	 * @param bool        $out    Irgendein komisches TCPDF-Ding. Kann ignoriert werden.
	 *
	 * @see TCPDF::SetFont()
	 */
	public function __construct(
		$name,
		$font,
		$style = '',
		$size = null,
		$subset = 'default',
		$out = true
	) {
		$this->name   = $name;
		$this->font   = $font;
		$this->style  = $style;
		$this->size   = $size;
		$this->subset = $subset;
		$this->out    = $out;
	}

	/**
	 * Erstellt CSS-Attribute anhand des Presets.
	 * @return string
	 */
	public function toCSS() {
		$style          = '';
		$textDecoration = '';

		if (!empty($this->font)) {
			$style .= "font-family: {$this->font->getName()};\n";
		}

		if (!empty($this->size)) {
			$style .= "font-size: {$this->size};\n";
		}

		if (stristr($this->style, TcpdfFont::STYLE_BOLD)) {
			$style .= "font-weight: bold;\n";
		}

		if (stristr($this->style, TcpdfFont::STYLE_UNDERLINE)) {
			$textDecoration .= ' underline';
		}

		if (stristr($this->style, TcpdfFont::STYLE_OVERLINE)) {
			$textDecoration .= ' overline';
		}

		if (stristr($this->style, TcpdfFont::STYLE_STRIKETHROUGH)) {
			$textDecoration .= ' line-through';
		}

		if (!empty($textDecoration)) {
			$style .= "text-decoration:{$textDecoration};\n";
		}

		if (stristr($this->style, TcpdfFont::STYLE_ITALICS)) {
			$style .= "font-style: italic\n";
		}

		return $style;
	}

	/**
	 * @return string
	 */
	public function GetName() {
		return $this->name;
	}

	/**
	 * @return TcpdfFont
	 */
	public function GetFont() {
		return $this->font;
	}

	/**
	 * @return string
	 */
	public function GetStyle() {
		return $this->style;
	}

	/**
	 * @return float
	 */
	public function GetSize() {
		return $this->size;
	}

	/**
	 * @return mixed
	 */
	public function GetSubset() {
		return $this->subset;
	}

	/**
	 * @return bool
	 */
	public function IsOut() {
		return $this->out;
	}

	/**
	 * @param TcpdfFont $font
	 */
	public function SetFont($font) {
		$this->font = $font;
	}

	/**
	 * @param string $style
	 */
	public function SetStyle($style) {
		$this->style = $style;
	}

	/**
	 * @param float $size
	 */
	public function SetSize($size) {
		$this->size = $size;
	}

	/**
	 * @param mixed $subset
	 */
	public function SetSubset($subset) {
		$this->subset = $subset;
	}

	/**
	 * @param bool $out
	 */
	public function SetOut($out) {
		$this->out = $out;
	}
}
