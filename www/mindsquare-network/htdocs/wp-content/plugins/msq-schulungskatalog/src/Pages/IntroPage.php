<?php


namespace Msq\Schulungskatalog\Pages;


use Msq\Schulungskatalog\ImageUtils;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PdfElement\PartnerImageRow;
use Msq\Schulungskatalog\PdfUtils;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\SchulungskatalogPDF;

/**
 * Die Einleitungsseite
 * @package Msq\Schulungskatalog\Pages
 */
class IntroPage extends DefaultPage {
	use ImageUtils;
	use PdfUtils;

	/** @var string */
	protected $title;

	/** @var string */
	protected $text;

	/** @var array */
	protected $contact;

	/** @var int */
	protected $contactImageSize;

	/** @var string[] */
	protected $partnerLogos;

	/**
	 * Konstruktor.
	 *
	 * @param string          $title Titel der Einleitungsseite
	 * @param string          $text  Inhalt der Einleitungsseite
	 * @param MsqPageSettings $pageSettings
	 */
	public function __construct(
		$title,
		$text,
		MsqPageSettings $pageSettings
	) {
		parent::__construct($title, $pageSettings, null);

		$this->contact          = $pageSettings->getContact();
		$this->contactImageSize = 40;
		$this->text             = $text;
		$this->partnerLogos     = $pageSettings->getPartnerLogos();
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		$pdf->SetTextColor(51, 51, 51);
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::INTRO_PRESET);
		$pdf->setCellHeightRatio(1.5);
		$pdf->setCellPaddings(10, 0, 10, 1);
		$tagvs = array(
			'p' => array(0 => array('n' => 0, 'h' => 0), 1 => array('n' => 5, 'h' => 1))
		);
		$pdf->setHtmlVSpace($tagvs);

		$pdf->writeHTML($this->text, true, false, true, true);

		$this->printIntroContact();

		$margins = $pdf->getMargins();
		$x       = $margins['left'];
		$w       = $pdf->getPageWidth() - $margins['left'] - $margins['right'];
		$pdf->SetY($pdf->getPageHeight() - $margins['bottom'] - 15 - 5 - 1);
		$this->MsqGradient($pdf, $x, $pdf->GetY(), $w);
		$pdf->SetY($pdf->GetY() + 6);

		$baseX = $pdf->getPageWidth() / 2;
		$baseY = $pdf->GetY();
		$w     = $pdf->getPageWidth();

		$partnerImageRow = new PartnerImageRow($this->partnerLogos, $baseX, $baseY, $w);
		$partnerImageRow->output($pdf);
	}

	/**
	 * Gibt den Ansprechpartner in der Einleitung aus.
	 */
	public function printIntroContact() {
		$pdf = $this->pdf;

		$url       = $this->contact['image'];
		$imageSize = $this->contactImageSize;
		$x         = $pdf->getPageWidth() / 2 - $imageSize / 2;
		$y         = $pdf->GetY();
		$pdf->imageInCircle($url, $x, $y, $imageSize);

		$pdf->SetY($pdf->getImageRBY() + 4.5);
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::INTRO_BOLD_PRESET);
		$pdf->Cell(0, 0, $this->contact['name'], 0, 1, 'C');
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::INTRO_PRESET);
		$pdf->Cell(0, 0, $this->contact['position'], 0, 1, 'C');
		$pdf->Cell(0, 0, $this->contact['email'], 0, 1, 'C', false, 'mailto:' . $this->contact['email']);
		$pdf->Cell(0, 0, $this->contact['tel'], 0, 2, 'C', false, 'tel:' . $this->contact['tel']);
	}
}
