<?php


namespace Msq\Schulungskatalog\Pages;


use Msq\Schulungskatalog\OutputBehavior\OutputBehaviorInterface;
use Msq\Schulungskatalog\OutputBehavior\SimpleOutputBehavior;
use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use Msq\Schulungskatalog\TcpdfFontHandler;

/**
 * Ein Seitentyp im Schulungskatalog.
 * Beschreibt, wie Header, Footer und Inhalt auszuspielen sind.
 *
 * @package Msq\Schulungskatalog\Pages
 */
abstract class Page {
	/** @var PDF */
	protected $pdf;

	/** @var TcpdfFontHandler */
	protected $fontHandler;

	/** @var OutputBehaviorInterface */
	protected $outputBehavior;

	/**
	 * Konstruktor.
	 *
	 * @param PageSettingsInterface        $pageSettings
	 * @param OutputBehaviorInterface|null $outputBehavior Falls null wird ein {@link SimpleOutputBehavior} erstellt, welches {@link getContent()} aufruft.
	 */
	public function __construct(
		PageSettingsInterface $pageSettings,
		OutputBehaviorInterface $outputBehavior = null
	) {
		$this->setOutputBehavior($outputBehavior);
		$this->fontHandler = $pageSettings->getFontHandler();
		$this->pdf         = $pageSettings->getPdf();
	}

	/**
	 * Wird vor dem Beginn einer neuen Seite ausgeführt.
	 */
	public function beforePage() {
	}

	/**
	 * Wird nach dem Ende einer Seite ausgeführt.
	 */
	public function afterPage() {
	}

	/**
	 * Spielt den Header aus.
	 */
	abstract public function printHeader();

	/**
	 * Ruft die Output-Methode des {@link OutputBehaviorInterface}s auf.
	 *
	 * @param PrintResult $printResult
	 */
	public function printContent(PrintResult $printResult) {
		$this->outputBehavior->output($this->pdf, $printResult);
	}

	/**
	 * Spielt den Hauptinhalt der Seite aus.
	 *
	 * @param PDF         $pdf
	 * @param PrintResult $printResult
	 */
	abstract public function getContent(PDF $pdf, PrintResult $printResult);

	/**
	 * Spielt den Footer der Seite aus.
	 */
	abstract public function printFooter();

	/**
	 * @return PDF
	 */
	public function getPdf() {
		return $this->pdf;
	}

	/**
	 * @param PDF $pdf
	 */
	public function setPdf(PDF $pdf) {
		$this->pdf = $pdf;
	}

	/**
	 * @return OutputBehaviorInterface
	 */
	public function getOutputBehavior() {
		return $this->outputBehavior;
	}

	/**
	 * @param OutputBehaviorInterface $outputBehavior
	 */
	public function setOutputBehavior(OutputBehaviorInterface $outputBehavior = null) {
		if ($outputBehavior === null) {
			$outputBehavior = new SimpleOutputBehavior([$this, 'getContent']);
		}

		$this->outputBehavior = $outputBehavior;
	}
}
