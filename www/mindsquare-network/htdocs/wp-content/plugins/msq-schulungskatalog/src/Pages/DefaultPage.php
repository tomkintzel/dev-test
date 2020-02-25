<?php


namespace Msq\Schulungskatalog\Pages;


use DateTime;
use Msq\Schulungskatalog\AssetTrait;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\OutputBehavior\OutputBehaviorInterface;
use Msq\Schulungskatalog\PdfUtils;
use Msq\Schulungskatalog\SchulungskatalogPDF;

/**
 * Eine Standard-Inhaltsseite im Schulungskatalog.
 * Das Gegenstück zu {@link TitlePage}.
 *
 * @package Msq\Schulungskatalog\Pages
 */
abstract class DefaultPage extends Page {
	use AssetTrait;
	use PdfUtils;

	/** @var SchulungskatalogPDF */
	protected $pdf;

	/** @var int Höhe des Headers */
	protected $headerImageHeight;

	/** @var int Höhe des Logos im Header */
	protected $headerLogoHeight;
	/** @var array Abstände vom Logo zu anderen Elementen */
	protected $headerLogoMargins;

	/** @var int Höhe des Seitentitels im Header */
	protected $headerTitleHeight;
	/** @var int Höhe des Farbverlaufs am Fuß des Headers */
	protected $headerGradientHeight;

	/** @var string Hintergrundbild des Headers */
	protected $headerImage;
	/** @var string Der Seitentitel */
	protected $headerTitle;
	/** @var string Das Logo */
	protected $logoImage;

	/** @var string CSS für die Gestaltung von Listen */
	protected $listStyle;

	/** @var string CSS für die Gestaltung des Texts */
	protected $fontStyles;

	/** @var OutputBehaviorInterface */
	protected $outputBehavior;

	/**
	 * Konstruktor.
	 *
	 * @param string                       $headerTitle Der Seitentitel
	 * @param MsqPageSettings              $pageSettings
	 * @param OutputBehaviorInterface|null $outputBehavior
	 */
	public function __construct(
		$headerTitle,
		MsqPageSettings $pageSettings,
		OutputBehaviorInterface $outputBehavior = null
	) {
		parent::__construct($pageSettings, $outputBehavior);

		$this->headerImage = $pageSettings->getHeaderImage();
		$this->headerTitle = $headerTitle;
		$this->logoImage   = $pageSettings->getHeaderLogo();

		$this->headerImageHeight    = 44;
		$this->headerLogoHeight     = 8;
		$this->headerLogoMargins    = [6, 6, 3, 6]; // Top, Right, Bottom, Left
		$this->headerTitleHeight    = 20;
		$this->headerGradientHeight = 1;

		$listImg         = $this->getAssetUrl() . 'images/list-item.png';
		$this->listStyle = <<<HTML
<style>
	ul {
		list-style-type: img|png|1.1|1.1|{$listImg}
	}
</style>
HTML;

		$h1Style   = $this->fontHandler->GetPreset(SchulungskatalogPDF::H1_PRESET)->toCSS();
		$h2Style   = $this->fontHandler->GetPreset(SchulungskatalogPDF::H2_PRESET)->toCSS();
		$h3Style   = $this->fontHandler->GetPreset(SchulungskatalogPDF::H3_PRESET)->toCSS();
		$textStyle = $this->fontHandler->GetPreset(SchulungskatalogPDF::TEXT_PRESET)->toCSS();

		$this->fontStyles = <<<HTML
<style>
h1 {
	{$h1Style}
}
h2 {
	{$h2Style}
}
h3 {
	{$h3Style}
}
p {
	{$textStyle}
}
</style>
HTML;
	}

	public function beforePage() {
		$tcpdf = $this->pdf;
		$tcpdf->SetMargins(20, $this->getHeaderHeight() + 20);
		$tcpdf->setCellPaddings(0, 0, 0, 0);
		$tcpdf->setListIndentWidth(3);
		$tcpdf->setHtmlVSpace([
			'ul' => [
				[
					'h' => 0
				],
				[
					'h' => 8
				]
			],
			'li' => [
				[
					'h' => 2
				]
			],
			'h2' => [
				[
					'h' => 0,
				],
				[
					'h' => 2,
				]
			],
			'p'  => [
				[
					'h' => 0
				],
				[
					'h' => 8
				]
			]
		]);
	}

	public function printHeader() {
		$tcpdf = $this->pdf;
		// Optimale Auflösung: 2480x520
		$this->printHeaderImage($this->headerImage);

		$this->MsqGradient($tcpdf, 0, 44);

		// Optimale Auflösung: *x71
		$this->printHeaderLogo($this->logoImage);
		$this->printHeaderTitle($this->headerTitle);
	}

	public function printFooter() {
		$this->printDate();
		$this->printPageNumber();
	}

	/**
	 * Gibt das Hintergrundbild des Headers aus.
	 *
	 * @param string $image Die Bild-URL
	 */
	public function printHeaderImage($image) {
		$this->pdf->Image(
			$image,
			0,
			0,
			$this->pdf->getPageWidth(),
			$this->headerImageHeight
		);

	}

	/**
	 * Spielt das Logo im Header aus.
	 *
	 * @param string $logo Die Bild-URL des Logos
	 */
	public function printHeaderLogo($logo) {
		$tcpdf       = $this->pdf;
		$logoMargins = $this->headerLogoMargins;
		$width       = $tcpdf->getPageWidth() - $logoMargins[1] - $logoMargins[3];
		$tcpdf->Image(
			$logo,
			$logoMargins[3],
			$logoMargins[0],
			$width,
			8,
			'', '', '', true, 300, '', false, false, 0,
			'RM'
		);
	}

	/**
	 * Spielt den Seitentitel aus
	 *
	 * @param string $text Der Seitentitel
	 */
	public function printHeaderTitle($text) {
		$tcpdf       = $this->pdf;
		$logoMargins = $this->headerLogoMargins;

		$margins = $tcpdf->getMargins();
		$w       = 0;
		$h       = $this->headerTitleHeight;
		$x       = $margins['left'];
		$y       = $logoMargins[0] + $this->headerLogoHeight + $logoMargins[2];
		$align   = 'C';
		$valign  = 'M';

		$tcpdf->getFontHandler()->ApplyPreset('h1');
		$tcpdf->SetTextColor(255, 255, 255);

		$tcpdf->setCellHeightRatio(1);
		$tcpdf->MultiCell(
			$w, $h, $text, 0, $align,
			false, 1, $x, $y,
			true, 0, false, true,
			$h, $valign
		);
	}

	public function getHeaderHeight() {
		return $this->headerImageHeight + $this->headerGradientHeight;
	}

	/**
	 * Spielt das Datum im Footer der Seite aus.
	 *
	 * @throws \Exception Fehler beim Erstellen des Datums. Sollte nicht auftreten können.
	 */
	public function printDate() {
		$tcpdf = $this->pdf;
		$tcpdf->getFontHandler()->SetFont('ubuntu', '', 6);
		$dateTime = new DateTime();
		$text     = 'Stand: ' . $dateTime->format('d.m.y');

		$margins = $tcpdf->getMargins();
		$w       = $tcpdf->GetStringWidth($text);
		$h       = $tcpdf->getStringHeight($w, $text);
		$x       = $margins['left'];
		$y       = $tcpdf->getPageHeight() - $h - 10;
		$tcpdf->Text($x, $y, $text);
	}

	/**
	 * Spielt die Seitenzahl aus.
	 */
	public function printPageNumber() {
		$tcpdf = $this->pdf;
		$tcpdf->getFontHandler()->SetFont('ubuntu', '', 10);
		$text = 'Seite ' . $tcpdf->PageNo();

		$margins = $tcpdf->getMargins();
		$w       = $tcpdf->GetStringWidth($text);
		$h       = $tcpdf->getStringHeight($w, $text);
		$x       = $tcpdf->getPageWidth() - $w - $margins['right'];
		$y       = $tcpdf->getPageHeight() - $h - 10;
		$tcpdf->Text($x, $y, $text);
	}
}
