<?php


namespace Msq\Schulungskatalog\OutputBehavior;


use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use function call_user_func_array;

/**
 * Gibt einen Hauptinhalt und eine Sidebar aus.
 *
 * @package Msq\Schulungskatalog\OutputBehavior
 */
class SidebarOutputBehavior extends SimpleOutputBehavior implements OutputBehaviorInterface {
	/** @var float Die Breite der Sidebar */
	protected $sideBarWidth;
	/** @var array Die Abstände der Sidebar */
	protected $sideBarMargins;
	/** @var int Die Größe des Referenten-Bilds */
	protected $speakerImageSize;

	/** @var callable Das Callback, mit welchem der Inhalt der Sidebar ausgespielt wird */
	protected $sidebarCallback;

	/**
	 * SidebarOutputBehavior constructor.
	 *
	 * @param callable $mainContentCallback Spielt den Hauptinhalt aus
	 * @param callable $sidebarCallback Spielt den Sidebar-Inhalt aus
	 */
	public function __construct(callable $mainContentCallback, callable $sidebarCallback) {
		parent::__construct($mainContentCallback);
		$this->sideBarWidth     = 46.6;
		$this->sideBarMargins   = [
			'top'     => 0,
			'right'   => 0,
			'bottom'  => 2,
			'left'    => 10,
			'element' => 8,
			'inner'   => 2
		];
		$this->speakerImageSize = 20;

		$this->sidebarCallback = $sidebarCallback;
	}

	public function output(PDF $tcpdf, PrintResult $printResult) {
		$margins = $tcpdf->getMargins();

		$tcpdf->SetTextColor(51, 51, 51);
		$w = $this->sideBarWidth;
		$x = -$margins['right'] - $w;
		$tcpdf->setCellPaddings(0, 0, 0, 0);

		$tcpdf->SetXY($x, $margins['top']);
		call_user_func_array($this->sidebarCallback, [
			$tcpdf,
			$printResult,
			$this->sideBarWidth,
			$this->sideBarMargins,
			$this->speakerImageSize
		]);

		$margins = $tcpdf->getMargins();
		$tcpdf->SetXY($margins['left'], $margins['top']);
		$rightPadding = $this->sideBarWidth + $this->sideBarMargins['left'];
		$tcpdf->setCellPaddings('', '', $rightPadding);
		$tcpdf->setCellHeightRatio(1.5);
		call_user_func_array($this->mainContentCallback, [$tcpdf, $printResult]);
	}

	/**
	 * @return float
	 */
	public function getSideBarWidth() {
		return $this->sideBarWidth;
	}

	/**
	 * @param float $sideBarWidth
	 */
	public function setSideBarWidth($sideBarWidth) {
		$this->sideBarWidth = $sideBarWidth;
	}

	/**
	 * @return array
	 */
	public function getSideBarMargins() {
		return $this->sideBarMargins;
	}

	/**
	 * @param array $sideBarMargins
	 */
	public function setSideBarMargins($sideBarMargins) {
		$this->sideBarMargins = $sideBarMargins;
	}

	/**
	 * @return int
	 */
	public function getSpeakerImageSize() {
		return $this->speakerImageSize;
	}

	/**
	 * @param int $speakerImageSize
	 */
	public function setSpeakerImageSize($speakerImageSize) {
		$this->speakerImageSize = $speakerImageSize;
	}

	/**
	 * @return mixed
	 */
	public function getSidebarCallback() {
		return $this->sidebarCallback;
	}

	/**
	 * @param mixed $sidebarCallback
	 */
	public function setSidebarCallback($sidebarCallback) {
		$this->sidebarCallback = $sidebarCallback;
	}
}
