<?php


namespace Msq\Schulungskatalog\Pages;

use Msq\Schulungskatalog\AssetTrait;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PdfUtils;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\SchulungskatalogPDF;

/**
 * Eine Titelseite wie z.B. die {@link CategoryTitlePage} und die {@link FrontPage}
 * @package Msq\Schulungskatalog\Pages
 */
class TitlePage extends Page {
	use AssetTrait;
	use PdfUtils;

	/** @var SchulungskatalogPDF */
	protected $pdf;

	/** @var string Der Haupttitel der Seite */
	protected $title;
	/** @var string Der Untertitel der Seite */
	protected $subTitle;
	/** @var string Die URL des Hintergrundbilds */
	protected $backgroundImageUrl;

	/**
	 * Konstruktor.
	 *
	 * @param string                $title              Der Titel der Seite
	 * @param string                $subTitle           Der Untertitel der Seite
	 * @param string                $backgroundImageUrl Die URL des Hintergrundbilds
	 * @param PageSettingsInterface $pageSettings
	 */
	public function __construct(
		$title,
		$subTitle,
		$backgroundImageUrl,
		PageSettingsInterface $pageSettings
	) {
		parent::__construct($pageSettings, null);
		$this->title              = $title;
		$this->subTitle           = $subTitle;
		$this->backgroundImageUrl = $backgroundImageUrl;
	}

	public function beforePage() {
		$this->pdf->SetMargins(20, 20);
		parent::beforePage();
	}

	public function printHeader() {
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		$margins = $pdf->getMargins();

		$w = $pdf->getPageWidth();
		$h = $pdf->getPageHeight();

		$breakMargin   = $pdf->getBreakMargin();
		$autoPageBreak = $pdf->getAutoPageBreak();
		$pdf->SetAutoPageBreak(false, 0);

		$pdf->Image(
			$this->backgroundImageUrl,
			0,
			0,
			$w,
			$h
		);

		$pdf->SetAutoPageBreak($autoPageBreak, $breakMargin);
		$pdf->setPageMark();

		$w -= $margins['left'] + $margins['right'];

		$pdf->SetY($margins['top'] + 30);
		$pdf->getFontHandler()->ApplyPreset('title');
		$pdf->SetTextColor(255, 255, 255);
		$pdf->setCellHeightRatio(1);

		$title = $this->title;
		$pdf->MultiCell($w, 0, $title, 0, 'C', false, 2, '', '', true, 0, false, true, 0, 'B');

		$pdf->SetY($pdf->GetY() + 2);
		$this->MsqGradient($pdf, $pdf->GetX(), $pdf->GetY(), $w, 1.5);

		if (is_string($this->subTitle)) {
			$pdf->SetY($pdf->GetY() + 3);
			$pdf->Cell($w, 0, $this->subTitle, 0, 2, 'C', false, '', 0, false, 'T', 'T');
		}
	}

	public function printFooter() {
	}
}
