<?php


namespace Msq\Schulungskatalog\Pages;

use DateTimeImmutable;
use Msq\Schulungskatalog\AssetTrait;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\SchulungskatalogPDF;

/**
 * Das Deckblatt des Schulungskatalogs.
 *
 * @package Msq\Schulungskatalog\Pages
 */
class FrontPage extends TitlePage {
	use AssetTrait;

	/** @var SchulungskatalogPDF */
	protected $pdf;

	/** @var string */
	protected $publisher;

	/**
	 * Konstruktor.
	 *
	 * @param string          $title           Der Titel des Schulungskatalogs
	 * @param string          $subtitle        Der Untertitel auf dem Deckblatt (in der Vorlage das jetzige Jahr)
	 * @param string          $publisher       Der Herausgeber-Text des Schulungskatalogs
	 * @param string          $backgroundImage Das Hintergrundbild des Deckblatts
	 * @param MsqPageSettings $pageSettings
	 */
	public function __construct(
		$title,
		$subtitle,
		$publisher,
		$backgroundImage,
		MsqPageSettings $pageSettings
	) {
		$this->publisher = $publisher;

		parent::__construct(
			$title,
			$subtitle,
			$backgroundImage,
			$pageSettings
		);
	}

	public function beforePage() {
		$this->pdf->SetMargins(20, 20);
		parent::beforePage();
	}

	public function printHeader() {
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		parent::getContent($pdf, $printResult);
		$margins = $pdf->getMargins();

		$url = $this->getAssetUrl() . 'images/logo.png';
		$w   = $pdf->getPageWidth() - $margins['left'] - $margins['right'];
		$h   = 20;

		$pdf->Image(
			$url,
			$margins['left'],
			$margins['top'],
			$w,
			$h,
			'', '', '', false, 300, '', false, false, 0,
			'CM'
		);

		$publishedByTitle = 'Herausgegeben von:';
		$publishedBy      = $this->publisher;

		/*
		Höhe des "Herausgegeben von: ..."-Teils berechnen.
		Hierfür muss auch die Schriftart eingestellt werden.
		*/
		$pdf->setCellHeightRatio(1.2);

		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::PUBLISHED_BOLD_PRESET);
		$height = $pdf->getStringHeight($w, $publishedByTitle);

		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::PUBLISHED_PRESET);
		$height += $pdf->getStringHeight($w, $publishedBy);

		$pdf->SetY($pdf->getPageHeight() - $margins['bottom'] - $height);

		// Ausgabe des Herausgebers
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::PUBLISHED_BOLD_PRESET);
		$pdf->Cell($w, 0, $publishedByTitle, 0, 2, 'C');
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::PUBLISHED_PRESET);
		$pdf->MultiCell($w, 0, $publishedBy, 0, 'C');

		// Automatischen Seitenumbruch den Stand deaktivieren
		$autoPageBreak = $pdf->getAutoPageBreak();
		$breakMargin   = $pdf->getBreakMargin();
		$pdf->SetAutoPageBreak($autoPageBreak, 0);
		$pdf->getFontHandler()->ApplyPreset(SchulungskatalogPDF::PUBLISH_DATE_PRESET);

		$date        = new DateTimeImmutable();
		$publishDate = 'Stand: ' . $date->format('d.m.Y');
		$y           = $pdf->getPageHeight() - 10;
		$pdf->SetY($y);
		$pdf->Cell($w, 0, $publishDate, 0, 0, 'C', false, '', 0, false, 'B', 'B');

		$pdf->SetAutoPageBreak($autoPageBreak, $breakMargin);
	}

	public function printFooter() {
	}
}
