<?php

namespace Msq\Schulungskatalog;

use Msq\Schulungskatalog\AcfLoader\CatalogAcfLoader;
use Msq\Schulungskatalog\AcfLoader\ConclusionAcfLoader;
use Msq\Schulungskatalog\AcfLoader\FrontPageAcfLoader;
use Msq\Schulungskatalog\AcfLoader\IntroPageAcfLoader;
use Msq\Schulungskatalog\Pages\ConclusionPage;
use Msq\Schulungskatalog\Pages\FrontPage;
use Msq\Schulungskatalog\Pages\IntroPage;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use WP_Post;
use const MSQ_SCHULUNGSKATALOG_FONT_DIR;

/**
 * Eine TCPDF-Klasse zur Ausgabe des Schulungskatalogs.
 *
 * @package Msq\Schulungskatalog
 */
class SchulungskatalogPDF extends PagePDF {
	use AssetTrait;

	const ACF_PREFIX = 'msqsc_';

	const TITLE_PRESET = 'title';
	const PUBLISHED_PRESET = 'published';
	const PUBLISHED_BOLD_PRESET = 'publishedB';
	const PUBLISH_DATE_PRESET = 'publishDate';

	const INTRO_PRESET = 'intro';
	const INTRO_BOLD_PRESET = 'introBold';

	const H1_PRESET = 'h1';
	const H2_PRESET = 'h2';
	const H3_PRESET = 'h3';
	const TEXT_PRESET = 'text';
	const SIDEBAR_PRESET = 'sidebar';
	const SIDEBAR_BOLD_PRESET = 'sidebarBold';

	protected $catalogACFL;
	protected $frontACFL;
	protected $introACFL;
	protected $conclusionACFL;

	protected $pageSettings;
	protected $seminarPageFactory;

	protected $frontPage;
	protected $introPage;
	protected $seminars;
	protected $conclusionPage;

	public function __construct(
		$orientation = 'P',
		$unit = 'mm',
		$format = 'A4',
		$unicode = true,
		$encoding = 'UTF-8',
		$diskcache = false,
		$pdfa = false
	) {
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

		$fontDir           = MSQ_SCHULUNGSKATALOG_FONT_DIR;
		$this->fontHandler = new TcpdfFontHandler($fontDir, $this);
		$this->setUpFonts();

		$this->SetMargins(20, 20);

		$this->catalogACFL    = new CatalogAcfLoader(self::ACF_PREFIX);
		$this->frontACFL      = new FrontPageAcfLoader(self::ACF_PREFIX);
		$this->introACFL      = new IntroPageAcfLoader(self::ACF_PREFIX);
		$this->conclusionACFL = new ConclusionAcfLoader(self::ACF_PREFIX);

		$this->setUpMetadata();

		$headerImage  = $this->catalogACFL->getHeaderImage();
		$partnerLogos = $this->catalogACFL->getPartnerLogos();
		$contact      = $this->catalogACFL->getContact();

		$pageSettings       = new MsqPageSettings(
			$this,
			$this->fontHandler,
			$headerImage,
			$this->getAssetUrl() . 'images/logo.png',
			$partnerLogos,
			$contact
		);
		$this->pageSettings = $pageSettings;

		$title           = $this->frontACFL->getTitle();
		$subtitle        = $this->frontACFL->getSubtitle();
		$publisher       = $this->frontACFL->getPublisher();
		$coverImage      = $this->frontACFL->getCoverImage();
		$this->frontPage = new FrontPage($title, $subtitle, $publisher, $coverImage, $pageSettings);

		$introTitle      = $this->introACFL->getTitle();
		$introText       = $this->introACFL->getText();
		$this->introPage = new IntroPage($introTitle, $introText, $pageSettings);

		$content              = $this->conclusionACFL->getContent();
		$awards               = $this->conclusionACFL->getAwards();
		$this->conclusionPage = new ConclusionPage($content, $awards, $pageSettings);

		$this->seminarPageFactory = new SeminarPageFactory($pageSettings);
	}

	/**
	 * Fügt eine Schulung zum Katalog hinzu.
	 *
	 * @param WP_Post|null $seminar
	 */
	public function addSeminar($seminar = null) {
		$this->seminars[] = $seminar;
	}

	/**
	 * Setzt die Schulungen des Katalogs.
	 *
	 * @param array $seminars
	 */
	public function setSeminars(array $seminars) {
		$this->seminars = $seminars;
	}

	/**
	 * Erstellt die Inhalte des Katalogs.
	 *
	 * @return PrintResult Resultat der Ausgabe
	 */
	public function printCatalog() {
		$pages        = [$this->frontPage, $this->introPage];
		$seminarPages = $this->seminarPageFactory->createPages($this->seminars);
		$pages        = array_merge($pages, $seminarPages);
		$pages[]      = $this->conclusionPage;
		$printResult  = new PrintResult();

		foreach ($pages as $page) {
			$this->outputPage($page, $printResult);
		}

		return $printResult;
	}

	/**
	 * Legt Metadaten für das PDF-Dokument fest.
	 */
	public function setUpMetadata() {
		$title = $this->frontACFL->getTitle();
		$title .= ' (' . $this->frontACFL->getSubtitle() . ')';
		$this->SetTitle($title);
		$this->SetAuthor('mindsquare');
	}

	/**
	 * Bereitet Schriftarten vor, die im Dokument genutzt werden.
	 */
	public function setUpFonts() {
		$ubuntuFilename     = 'Ubuntu-Regular.ttf';
		$ubuntuBoldFilename = 'Ubuntu-Bold.ttf';

		$ubuntuFont     = $this->fontHandler->AddFont('ubuntu', $ubuntuFilename);
		$ubuntuBoldFont = $this->fontHandler->AddFont('ubuntu', $ubuntuBoldFilename, TcpdfFont::STYLE_BOLD);

		$titlePreset = new FontPreset(
			self::TITLE_PRESET,
			$ubuntuFont,
			'B',
			55
		);
		$this->fontHandler->AddPreset($titlePreset);

		$publishedPreset = new FontPreset(
			self::PUBLISHED_PRESET,
			$ubuntuFont,
			'',
			12
		);
		$this->fontHandler->AddPreset($publishedPreset);

		$publishedBoldPreset = new FontPreset(
			self::PUBLISHED_BOLD_PRESET,
			$ubuntuBoldFont,
			'B',
			12
		);
		$this->fontHandler->AddPreset($publishedBoldPreset);

		$publishDatePreset = new FontPreset(
			self::PUBLISH_DATE_PRESET,
			$ubuntuBoldFont,
			'B',
			10
		);
		$this->fontHandler->AddPreset($publishDatePreset);

		$introPreset = new FontPreset(
			self::INTRO_PRESET,
			$ubuntuFont,
			'',
			12
		);
		$this->fontHandler->AddPreset($introPreset);

		$introBoldPreset = new FontPreset(
			self::INTRO_BOLD_PRESET,
			$ubuntuBoldFont,
			TcpdfFont::STYLE_BOLD,
			12
		);
		$this->fontHandler->AddPreset($introBoldPreset);

		$h1Preset = new FontPreset(
			self::H1_PRESET,
			$ubuntuFont,
			'B',
			26
		);
		$this->fontHandler->AddPreset($h1Preset);

		$h2Preset = new FontPreset(
			self::H2_PRESET,
			$ubuntuFont,
			'B',
			12
		);
		$this->fontHandler->AddPreset($h2Preset);

		$h3Preset = new FontPreset(
			self::H3_PRESET,
			$ubuntuFont,
			'B',
			10
		);
		$this->fontHandler->AddPreset($h3Preset);

		$textPreset = new FontPreset(
			self::TEXT_PRESET,
			$ubuntuFont,
			'',
			10
		);
		$this->fontHandler->AddPreset($textPreset);

		$sidebarPreset = new FontPreset(
			self::SIDEBAR_PRESET,
			$ubuntuFont,
			'',
			8
		);
		$this->fontHandler->AddPreset($sidebarPreset);

		$sidebarBoldPreset = new FontPreset(
			self::SIDEBAR_BOLD_PRESET,
			$ubuntuFont,
			TcpdfFont::STYLE_BOLD,
			8
		);
		$this->fontHandler->AddPreset($sidebarBoldPreset);
	}

}
