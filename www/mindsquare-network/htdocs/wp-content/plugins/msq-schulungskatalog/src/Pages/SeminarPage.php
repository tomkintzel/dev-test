<?php


namespace Msq\Schulungskatalog\Pages;


use DateTimeImmutable;
use Msq\Schulungskatalog\FontUtils;
use Msq\Schulungskatalog\ImageUtils;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\OutputBehavior\SidebarOutputBehavior;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\ErrorSeverity;
use Msq\Schulungskatalog\PrintResult\PrintError;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\SchulungskatalogPDF;

/**
 * Eine Schulungsseite.
 *
 * @package Msq\Schulungskatalog\Pages
 */
class SeminarPage extends DefaultPage {
	use ImageUtils;
	use FontUtils;

	/** @var SchulungskatalogPDF */
	protected $pdf;

	/** @var array Ein Array mit den Höhen der Content-Elemente */
	protected $contentHeights;
	/** @var float Die Breite des Contents */
	protected $contentWidth;
	/** @var array Ein Array mit den Abständen der Content-Elemente */
	protected $contentBottomMargins;

	/** @var SidebarOutputBehavior */
	protected $outputBehavior;

	/** @var SeminarSettingsInterface */
	protected $seminarPageSettings;

	/**
	 * Konstruktor.
	 * Im Normalfall sollten Seminarseiten über die {@link SeminarPageFactory} erstellt werden.
	 *
	 * @param SeminarSettingsInterface $seminarPageSettings
	 * @param MsqPageSettings          $pageSettings
	 * @param string|null              $headerImage Die URL des Header-Bilds der Schulungskategorie
	 */
	public function __construct(
		SeminarSettingsInterface $seminarPageSettings,
		MsqPageSettings $pageSettings,
		$headerImage = null
	) {
		$this->seminarPageSettings = $seminarPageSettings;

		if (empty($headerImage)) {
			$headerImage = $this->getAssetUrl() . 'images/category-header.png';
		}

		$pageSettings->setHeaderImage($headerImage);
		$headerTitle = $seminarPageSettings->getTitle();

		$outputBehavior = new SidebarOutputBehavior([$this, 'getContent'], [$this, 'getSidebar']);
		parent::__construct($headerTitle, $pageSettings, $outputBehavior);

		$this->contentHeights       = [78, 61];
		$this->contentWidth         = 113.2;
		$this->contentBottomMargins = [10, 10];
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		$margins = $pdf->getMargins();
		$tagvs = array(
			'p' => array(0 => array('n' => 0, 'h' => 0), 1 => array('n' => 5, 'h' => 1))
		);
		$pdf->setHtmlVSpace($tagvs);

		$text = $this->listStyle . $this->fontStyles . '<h2>Ihr Ziel</h2>';
		$text .= $this->seminarPageSettings->getGoal();

		$pdf->getFontHandler()->ApplyPreset('text');
		$pdf->WriteHTML($text, true, false, false, true);

		$maxY = $margins['top'] + $this->contentHeights[0];

		if ($pdf->GetY() > $maxY) {
			$error = new PrintError(
				ErrorSeverity::NOTICE,
				sprintf(
					'Der "Ihr Ziel"-Inhalt der Schulung "%s" ist zu lang.',
					$this->seminarPageSettings->getTitle()
				),
				$pdf->getPage()
			);
			$printResult->addError($error);
		}

		$y = min($pdf->GetY(), $maxY);
		$y += $this->contentBottomMargins[0];
		$pdf->SetY($y);

		$text = $this->listStyle;
		$text .= $this->fontStyles;
		$text .= '<h2>Schulungsinhalte</h2>';
		$text .= $this->seminarPageSettings->getTopics();
		$pdf->getFontHandler()->ApplyPreset('text');
		$pdf->writeHTML($text, true, false, false, true);

		$maxY += $this->contentBottomMargins[0] + $this->contentHeights[1];

		if ($pdf->GetY() > $maxY) {
			$error = new PrintError(
				ErrorSeverity::NOTICE,
				sprintf(
					'Die Schulungsinhalte der Schulung "%s" sind zu lang.',
					$this->seminarPageSettings->getTitle()
				),
				$pdf->getPage()
			);
			$printResult->addError($error);
		}

		$this->printAppointmentTable();
	}

	/**
	 * Gibt die Tabelle mit den Schulungsterminen am Ende der Seite aus.
	 */
	public function printAppointmentTable() {
		$tcpdf      = $this->pdf;
		$originalHR = $tcpdf->getCellHeightRatio();
		$margins    = $tcpdf->getMargins();
		$w          = ($tcpdf->getPageWidth() - $margins['left'] - $margins['right']) / 3;

		$duration         = $this->seminarPageSettings->getDuration();
		$appointmentDates = $this->seminarPageSettings->getAppointments();
		$time             = $this->seminarPageSettings->getTime();
		$price            = number_format( $this->seminarPageSettings->getPrice(), 0, ',', '.' ) . ' € p. Teilnehmer';
		$inhousePrice     = number_format( $this->seminarPageSettings->getInhousePrice(), 0, ',', '.' ) . ' €';

		$appointments     = [];

		foreach ($appointmentDates as $appointmentDate) {
			if ($duration->d > 1) {
				/** @var DateTimeImmutable $endDate */
				$endDate    = $appointmentDate->add($duration);
				$dateString = $appointmentDate->format('d');
				$dateString .= '.–';
				$dateString .= $endDate->format('d.m.Y');
			} else {
				$dateString = $appointmentDate->format('d.m.Y');
			}

			if (!empty($time)) {
				$dateString .= ', ' . $time . ' Uhr';
			}

			$appointments[] = $dateString;
		}

		$location = 'mindsquare, Willy-Brandt-Platz 2, 33602 Bielefeld';

		$lastRow = [
			'Individuell',
			'bei Ihnen vor Ort',
			$inhousePrice
		];

		$appointmentCount = min(4, count($appointments));
		for ($i = 0; $i < $appointmentCount; $i++) {
			$rows[] = [$appointments[$i]];
		}

		$rows[0][] = $location;
		$rows[0][] = $price;
		$rows[]    = $lastRow;
		$rowCount  = count($rows);

		$paddings = [3, 2.5, 3, 2.5];

		$tcpdf->getFontHandler()->ApplyPreset('h2');
		$lineHeightAdjustment = $this->getLineHeightMarginInUnits(
			$tcpdf->getCellHeightRatio(),
			$tcpdf->getFontSize()
		);

		$headerPaddings = [
			$paddings[0],
			$paddings[1] - $lineHeightAdjustment,
			$paddings[2],
			$paddings[3] - $lineHeightAdjustment
		];

		$tcpdf->setCellPaddings(...$headerPaddings);
		$headerHeight = $tcpdf->getCellHeight($tcpdf->getFontSize());

		$tcpdf->getFontHandler()->ApplyPreset('text');

		$tcpdf->setCellHeightRatio(1.5);
		$lineHeightAdjustment = $this->getLineHeightMarginInUnits(
			$tcpdf->getCellHeightRatio(),
			$tcpdf->getFontSize()
		);

		$bodyPaddings = [
			$paddings[0],
			$paddings[1] - $lineHeightAdjustment,
			$paddings[2],
			$paddings[3] - $lineHeightAdjustment
		];
		$tcpdf->setCellPaddings(...$bodyPaddings);

		$cellHeight = $tcpdf->getCellHeight($tcpdf->getFontSize());
		$bodyHeight = max(
			$cellHeight * ($rowCount - 1),
			$tcpdf->getStringHeight($w, $location)
		);

		if ($cellHeight * $appointmentCount < $bodyHeight) {
			$bodyRowHeight = $bodyHeight;
		} else {
			$bodyRowHeight = $cellHeight;
		}

		$i           = 0;
		$borderStyle = [
			'color' => [186, 186, 186],
			'dash'  => true
		];

		$tableHeight = $headerHeight + .35 + $bodyRowHeight * $appointmentCount + $cellHeight;

		$tcpdf->SetY(-($margins['bottom'] + $tableHeight));

		$tcpdf->getFontHandler()->ApplyPreset('h2');
		$tcpdf->setCellPaddings(...$headerPaddings);

		$tcpdf->Cell($w, 0, 'Terminoptionen');
		$tcpdf->Cell($w, 0, 'Ort');
		$tcpdf->Cell($w, 0, 'Kosten');
		$tcpdf->Ln();

		$this->MsqGradient($tcpdf, $tcpdf->GetX(), $tcpdf->GetY(), $w * 3, .35);

		$tcpdf->getFontHandler()->ApplyPreset('text');
		$tcpdf->setCellPaddings(...$bodyPaddings);

		foreach ($rows as $row) {
			$borderSides  = 'R';
			$lastColSides = '';

			if ($i !== $rowCount - 1) {
				$borderSides  .= 'B';
				$lastColSides .= 'B';
				$h            = $bodyRowHeight;
			} else {
				$h = $cellHeight;
			}

			$border        = [$borderSides => $borderStyle];
			$lastColBorder = [$lastColSides => $borderStyle];

			$tcpdf->Cell($w, $h, $row[0], $border, 0, 'L', false, '', 0, false, 'T', 'T');

			if (isset($row[1])) {
				if ($i === 0) {
					$tcpdf->MultiCell($w, $bodyHeight, $row[1], $border, 'L', false, 0);
				} else {
					$tcpdf->Cell($w, $h, $row[1], $border, 0, 'L');
				}
			}

			if (isset($row[2])) {
				if ($i === 0) {
					$tcpdf->MultiCell($w, $bodyHeight, $row[2], $lastColBorder, 'L', false, 0);
				} else {
					$tcpdf->Cell($w, $h, $row[2], $lastColBorder, 0, 'L');
				}
			}

			$tcpdf->Ln($h);
			$i++;
		}

		$tcpdf->setCellHeightRatio($originalHR);
	}

	public function getSidebar(PDF $pdf, PrintResult $printResult, $w, $sideBarMargins, $speakerImageSize) {
		$x = $pdf->GetX();
		$pdf->setCellHeightRatio(1.5);

		$pdf->getFontHandler()->ApplyPreset('h3');
		$pdf->Cell($w, 0, 'Schulungsdauer', 0, 2);

		$pdf->getFontHandler()->ApplyPreset('sidebar');
		$duration       = $this->seminarPageSettings->getDuration();
		$durationString = $duration->d . ' Tag';

		if ($duration->d > 1) {
			$durationString .= 'e';
		}

		$pdf->Cell($w, 0, $durationString, 0, 2);

		$pdf->getFontHandler()->ApplyPreset('h3');
		$pdf->SetY($pdf->GetY() + $sideBarMargins['element'], false);
		$pdf->Cell($w, 0, 'Ihr Referent', 0, 2);

		$pdf->SetY($pdf->GetY() + $sideBarMargins['inner'], false);


		$speaker = $this->seminarPageSettings->getSpeaker();

		// Optimale Auflösung: 236x236
		$url          = $speaker['image'];
		$speakerImage = $this->imageFromUrl($url);
		$cropSize     = min(imagesx($speakerImage), imagesy($speakerImage));
		$speakerImage = $this->cropImage($speakerImage, $cropSize, $cropSize, .5, 0);
		$speakerImage = $this->imageToString($speakerImage);

		$pdf->StartTransform();
		$imageRadius = $speakerImageSize / 2;

		$pdf->Circle(
			$x + $imageRadius,
			$pdf->GetY() + $imageRadius,
			$imageRadius, 0, 360, 'CNZ');

		$pdf->Image(
			'@' . $speakerImage,
			$x, $pdf->GetY(), $speakerImageSize, $speakerImageSize,
			'', $url, '', true
		);
		$pdf->StopTransform();

		$pdf->getFontHandler()->ApplyPreset('sidebarBold');
		$pdf->SetXY($x, $pdf->GetY() + $speakerImageSize + $sideBarMargins['inner']);
		$pdf->Cell($w, 0, $speaker['name'], 0, 2);

		$pdf->getFontHandler()->ApplyPreset('sidebar');
		$pdf->SetX($x);
		$pdf->MultiCell(
			$w, 0,
			$speaker['position'],
			0, 'L', false, 0, '', '', false, 0, false, true,
			0, 'T', false
		);
		$pdf->Ln();

		$certificateImage = $this->seminarPageSettings->getCertificate();

		if (!empty($certificateImage)) {
			$pdf->getFontHandler()->ApplyPreset('h3');
			$pdf->SetXY($x, $pdf->GetY() + $sideBarMargins['element']);
			$pdf->Cell($w, 0, 'Abschlusszertifikat');
			$pdf->Ln();

			$pdf->SetXY($x, $pdf->GetY() + $sideBarMargins['inner']);
			$pdf->Image(
				$certificateImage,
				$x, $pdf->GetY(), 40, 25, '', $certificateImage,
				'T', false, 300, '', false, false,
				0, 'LT'
			);
		}
	}

	public function printHeader() {
		$pdf             = $this->pdf;
		$backgroundImage = $this->getAssetUrl('images/fancy-object.png');

		$w = $pdf->getPageWidth();
		$h = $pdf->getPageHeight();

		$breakMargin   = $pdf->getBreakMargin();
		$autoPageBreak = $pdf->getAutoPageBreak();
		$pdf->SetAutoPageBreak(false, 0);

		$pdf->Image(
			$backgroundImage,
			0,
			0,
			$w,
			$h
		);

		$pdf->SetAutoPageBreak($autoPageBreak, $breakMargin);
		$pdf->setPageMark();

		parent::printHeader(); // TODO: Change the autogenerated stub
	}


}
