<?php


namespace Msq\Schulungskatalog\Pages;


use Msq\Schulungskatalog\ImageUtils;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\OutputBehavior\SidebarOutputBehavior;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PdfElement\MsqGradient;
use Msq\Schulungskatalog\PdfElement\PartnerImageRow;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\SchulungskatalogPDF;
use Msq\Schulungskatalog\TcpdfFont;

/**
 * Die letzte Seite des Schulungskatalogs.
 *
 * @package Msq\Schulungskatalog\Pages
 */
class ConclusionPage extends DefaultPage {
	use ImageUtils;

	protected $content;
	protected $contact;
	protected $awards;
	protected $partnerLogos;

	protected $awardHeight = 30;

	/**
	 * Konstruktor.
	 *
	 * @param string          $content Der Hauptinhalt der Seite.
	 * @param array           $awards  Ein Array mit Bild-URLs, die am Ende der Seite ausgespielt werden
	 * @param MsqPageSettings $pageSettings
	 */
	public function __construct(
		$content,
		$awards,
		MsqPageSettings $pageSettings
	) {
		$this->content      = $content;
		$this->contact      = $pageSettings->getContact();
		$this->awards       = $awards;
		$this->partnerLogos = $pageSettings->getPartnerLogos();

		$title          = 'Ihr gesuchtes Thema war nicht dabei?';
		$outputBehavior = new SidebarOutputBehavior([$this, 'getContent'], [$this, 'getSidebar']);
		$outputBehavior->setSpeakerImageSize(20);

		parent::__construct($title, $pageSettings, $outputBehavior);
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		$margins = $pdf->getMargins();
		$w       = $this->getPageInnerWidth($pdf);
		$halfX   = $pdf->getPageWidth() / 2;

		$tagvs = array(
			'p' => array(0 => array('n' => 0, 'h' => 0), 1 => array('n' => 5, 'h' => 1))
		);
		$pdf->setHtmlVSpace($tagvs);
		$partnerImageRow = new PartnerImageRow($this->partnerLogos, $halfX);
		$gradient        = new MsqGradient($margins['left']);
		$bottomMargin    = $margins['bottom'] + $this->awardHeight + $partnerImageRow->getH() + $gradient->getH() + 2 * 5;

		$autoPageBreak = $pdf->getAutoPageBreak();
		$breakMargin   = $pdf->getBreakMargin();
		$pdf->SetAutoPageBreak(true, $bottomMargin + 5);
		$this->fontHandler->ApplyPreset(SchulungskatalogPDF::TEXT_PRESET);

		$html = $this->fontStyles . $this->listStyle . $this->content;
		$pdf->writeHTML($html, true, false, false, true);

		$pdf->SetAutoPageBreak(false, 0);
		$pdf->SetY($pdf->getPageHeight() - $bottomMargin);

		$rows = $this->imageRow($this->awards, $w, $this->awardHeight, 10);

		$baseY = $pdf->GetY();
		$this->outputImageRows($pdf, $rows, $halfX, $baseY);

		$y = $pdf->getImageRBY() + 5;

		$gradient->setY($y);
		$gradient->setW($w);
		$gradient->output($pdf);

		$partnerImageRow->setBaseY($y + 5);
		$partnerImageRow->setW($w);
		$partnerImageRow->output($pdf);

		$pdf->SetAutoPageBreak($autoPageBreak, $breakMargin);
	}

	/**
	 * Gibt die Sidebar dieser Seite aus.
	 *
	 * @param PDF         $pdf         Die PDF-Instanz
	 * @param PrintResult $printResult Das Ausgabeergebnis.
	 * @param int         $w           Die Breite der Sidebar
	 * @param array       $sideBarMargins Abstände der Sidebar. Assoziatives Array im folgenden Format:
	 * <pre><code>[
	 *     'inner'   => Abstand innerhalb eines Elements im Sidebar,
	 *     'element' => Abstand zwischen zwei Elementen,
	 *     'bottom'  => Abstand nach unten
	 * ]
	 * </pre></code>
	 * @param int         $speakerImageSize
	 */
	public function getSidebar(PDF $pdf, PrintResult $printResult, $w, $sideBarMargins, $speakerImageSize) {
		$pdf->setCellMargins(0, 0, 0, $sideBarMargins['bottom']);

		$x = $pdf->GetX();

		$this->fontHandler->ApplyPreset(SchulungskatalogPDF::H3_PRESET);
		$pdf->Cell($w, 0, 'Ihr Ansprechpartner', 0, 2);

		$pdf->imageInCircle($this->contact['image'], $pdf->GetX(), $pdf->GetY(), $speakerImageSize);

		$pdf->setCellMargins(0, 0, 0, 0);
		$pdf->SetY($pdf->getImageRBY() + $sideBarMargins['inner'], false);

		$this->fontHandler->ApplyPreset(SchulungskatalogPDF::SIDEBAR_BOLD_PRESET);
		$pdf->Cell($w, 0, $this->contact['name'], 0, 2);

		$this->fontHandler->ApplyPreset(SchulungskatalogPDF::SIDEBAR_PRESET);
		$pdf->MultiCell($w, 0, $this->contact['position'], 0, 'L', false, 1);
		$pdf->SetX($x);

		$pdf->SetTextColor(27, 94, 162);
		$this->fontHandler->SetFont(null, TcpdfFont::STYLE_UNDERLINE);
		$pdf->Cell($w, 0, $this->contact['url'], 0, 2, '', false, $this->contact['url']);
		$pdf->SetTextColor(51, 51, 51);

		$this->fontHandler->ApplyPreset(SchulungskatalogPDF::SIDEBAR_PRESET);
		$pdf->Cell($w, 0, $this->contact['email'], 0, 2);
		$pdf->Cell($w, 0, $this->contact['tel'], 0, 2);

	}
}
