<?php


namespace Msq\Schulungskatalog\Pages;


use Msq\Schulungskatalog\AcfLoader\SeminarAcfLoader;
use Msq\Schulungskatalog\FontPreset;
use Msq\Schulungskatalog\FontUtils;
use Msq\Schulungskatalog\MsqPageSettings;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PdfUtils;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\TcpdfFont;
use WP_Post;
use WP_Term;

/**
 * Eine Übersichtsseite mit einer Auflistung aller Seminare.
 * Sollte im Regelfall von der {@link SeminarPageFactory} generiert werden.
 * @package Msq\Schulungskatalog\Pages
 */
class SeminarOverviewPage extends DefaultPage {
	use FontUtils;
	use PdfUtils;

	/** @var array */
	protected $preparedSeminars;

	/** @var FontPreset Überschrift der Tabelle */
	protected $tableHeaderPreset;
	/** @var FontPreset Unterüberschrift der Tabelle (bspw. "(Bielefeld)" bei "Kosten p. Teilnehmer"  */
	protected $tableSubHeaderPreset;
	/** @var FontPreset Kategorieüberschriften */
	protected $tableCategoryHeaderPreset;
	/** @var FontPreset Tabelleninhalt */
	protected $tableContentPreset;
	/** @var FontPreset Hervorgehobener Tabelleninhalt */
	protected $tableBoldContentPreset;

	/** @var int */
	protected $tablePadding;

	/** @var int */
	protected $seminarColWidth;
	/** @var int */
	protected $durationColWidth;
	/** @var int */
	protected $priceColWidth;
	/** @var int */
	protected $totalColWidth;
	/** @var int */
	protected $tableWidth;

	/** @var array */
	protected $borderStyle;

	/**
	 * Konstruktor.
	 *
	 * @param array           $preparedSeminars Eine Liste der bereits sortierten Schulungen und Schulungskategorien.
	 * @param MsqPageSettings $pageSettings
	 */
	public function __construct(array $preparedSeminars, MsqPageSettings $pageSettings) {
		$title                  = 'Alle Schulungen im Überblick';
		$this->preparedSeminars = $preparedSeminars;
		parent::__construct($title, $pageSettings, null);

		$this->tablePadding = 3;

		$this->seminarColWidth  = 50;
		$this->durationColWidth = 20;
		$this->priceColWidth    = 50;
		$this->totalColWidth    = 50;

		$this->tableWidth = $this->seminarColWidth + $this->durationColWidth + $this->priceColWidth + $this->totalColWidth;

		$this->borderStyle = [
			'width' => .35,
			'dash'  => true,
			'color' => [198, 198, 198]
		];

		$this->setUpFonts();
	}

	public function getContent(PDF $pdf, PrintResult $printResult) {
		$lineHeight = 1.5;
		$padding    = $this->tablePadding;
		$pdf->setCellHeightRatio($lineHeight);

		$this->fontHandler->ApplyPreset($this->tableHeaderPreset);
		$vPadding = $this->adjustPaddingForLineHeight($padding, $lineHeight, $pdf->getFontSize());
		$pdf->setCellPaddings($padding, $vPadding, $padding, $vPadding);

		$headerHeight = $pdf->getCellHeight($pdf->getFontSize(), false) * 2 + $vPadding * 2;

		$pdf->Cell($this->seminarColWidth, $headerHeight, 'Schulung');
		$pdf->Cell($this->durationColWidth, $headerHeight, 'Dauer');

		$pdf->cellColumn($this->priceColWidth, $headerHeight, $padding, [
			[
				'txt'        => 'Kosten p. Teilnehmer',
				'fontPreset' => $this->tableHeaderPreset
			],
			[
				'txt'        => '(Bielefeld)',
				'fontPreset' => $this->tableSubHeaderPreset
			]
		]);

		$pdf->cellColumn($this->totalColWidth, $headerHeight, $padding, [
			[
				'txt'        => 'Kosten gesamt',
				'fontPreset' => $this->tableHeaderPreset
			],
			[
				'txt'        => '(vor Ort, individualisiert)',
				'fontPreset' => $this->tableSubHeaderPreset
			]
		], 1);

		$w = $this->tableWidth;
		$h = .35;
		$this->MsqGradient($pdf, $pdf->getX(), $pdf->getY() - $h, $w, $h);

		$seminarACFL = new SeminarAcfLoader('');

		/**
		 * @var int     $id
		 * @var WP_Term $term
		 */
		foreach ($this->preparedSeminars['terms'] as $id => $term) {
			$this->printCategoryHeader($term->name);

			/** @var WP_Post $seminar */
			foreach ($this->preparedSeminars['seminars'][$id] as $seminar) {
				$seminarACFL->setObject($seminar);
				$days = $seminarACFL->getDuration()->d;

				$this->printSeminarRow([
					'text'     => $seminarACFL->getTitle(),
					'duration' => $days . ' Tag' . ($days > 1 ? 'e' : ''),
					'price'    => $seminarACFL->getPrice(),
					'total'    => $seminarACFL->getInhousePrice()
				]);
			}
		}
	}

	/**
	 * Gibt den Titel einer Kategorie aus.
	 *
	 * @param $title
	 */
	public function printCategoryHeader($title) {
		$pdf     = $this->pdf;
		$padding = $this->tablePadding;
		$w       = $this->tableWidth;

		$this->fontHandler->ApplyPreset($this->tableCategoryHeaderPreset);
		$vPadding = $this->adjustPaddingForLineHeight($padding, $pdf->getCellHeightRatio(), $pdf->getFontSize());
		$pdf->setCellPaddings($padding, $vPadding, $padding, $vPadding);
		$pdf->Cell($w, 0, $title, [
			'B' => $this->borderStyle
		], 1);
	}

	/**
	 * Gibt eine Zeile für eine Schulung aus.
	 *
	 * @param array $seminar Ein assoziatives Array im folgenden Format:
	 * <pre><code>[
	 *    'text'     => Titel des Seminars,
	 *    'duration' => Die Dauer des Seminars in Tagen,
	 *    'price'    => Der Preis eines Seminars pro Person in Bielefeld
	 *    'total'    => Der Preis eines Seminars pauschal vor Ort
	 * ]</code></pre>
	 */
	public function printSeminarRow($seminar) {
		$pdf     = $this->pdf;
		$padding = $this->tablePadding;
		$margins = $pdf->getMargins();

		$this->fontHandler->ApplyPreset($this->tableContentPreset);
		$vPadding = $this->adjustPaddingForLineHeight($padding, $pdf->getCellHeightRatio(), $pdf->getFontSize());
		$pdf->setCellPaddings($padding, $vPadding, $padding, $vPadding);

		$text     = $seminar['text'];
		$duration = $seminar['duration'];
		$price    = number_format($seminar['price'], 0, ',', '.') . ' €';
		$total    = number_format($seminar['total'], 0, ',', '.') . ' €';

		$h           = $pdf->getStringHeight($this->seminarColWidth, $text, false, true, '',
			['RB' => $this->borderStyle]);
		$borderSides = 'B';

		if ($pdf->getPageHeight() - $margins['bottom'] < $pdf->GetY() + $h) {
			$pdf->AddPage();
			$pdf->SetY($margins['top']);
			$borderSides .= 'T';
		}

		$pdf->MultiCell($this->seminarColWidth, $h, $text, [$borderSides . 'R' => $this->borderStyle], 'L', false, 0);

		$pdf->Cell($this->durationColWidth, $h, $duration, [$borderSides . 'R' => $this->borderStyle]);

		$this->fontHandler->ApplyPreset($this->tableBoldContentPreset);
		$vPadding = $this->adjustPaddingForLineHeight($padding, $pdf->getCellHeightRatio(), $pdf->getFontSize());
		$pdf->setCellPaddings($padding, $vPadding, $padding, $vPadding);

		$pdf->Cell($this->priceColWidth, $h, $price, [$borderSides . 'R' => $this->borderStyle]);
		$pdf->Cell($this->totalColWidth, $h, $total, [$borderSides => $this->borderStyle], 1);
	}

	protected function setUpFonts() {
		$this->tableHeaderPreset = new FontPreset(
			'tableHeaderPreset',
			null,
			TcpdfFont::STYLE_BOLD,
			10
		);

		$this->tableSubHeaderPreset = new FontPreset(
			'tableSubHeaderPreset',
			null,
			'',
			null
		);

		$this->tableCategoryHeaderPreset = new FontPreset(
			'tableCategoryHeader',
			null,
			TcpdfFont::STYLE_BOLD,
			12
		);

		$this->tableContentPreset = new FontPreset(
			'tableContent',
			null,
			'',
			10
		);

		$this->tableBoldContentPreset = new FontPreset(
			'tableBoldContent',
			null,
			TcpdfFont::STYLE_BOLD,
			null
		);
	}
}
