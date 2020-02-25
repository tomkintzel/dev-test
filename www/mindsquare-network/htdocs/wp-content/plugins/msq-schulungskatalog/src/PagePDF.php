<?php


namespace Msq\Schulungskatalog;


use Msq\Schulungskatalog\Pages\Page;
use Msq\Schulungskatalog\PrintResult\PrintResult;

/**
 * Eine PDF-Instanz zur Ausgabe von {@link Page}-Instanzen.
 * @package Msq\Schulungskatalog
 */
class PagePDF extends PDF {
	/** @var Page */
	protected $pageObject;


	/**
	 * @param Page        $page        Die Seite, welche ausgegeben werden soll
	 * @param PrintResult $printResult Die PrintResult-Instanz des jetzigen Ausgabevorgangs
	 * @param bool        $keepMargins Siehe Dokumentation von {@link AddPage()}
	 */
	public function outputPage(Page $page, PrintResult $printResult, $keepMargins = false) {
		if (!isset($this->original_lMargin) OR $keepMargins) {
			$this->original_lMargin = $this->lMargin;
		}

		if (!isset($this->original_rMargin) OR $keepMargins) {
			$this->original_rMargin = $this->rMargin;
		}

		if ($this->pageObject instanceof Page) {
			$this->pageObject->setPdf($this);
		}

		$page->setPdf($this);

		$this->endPage();
		$this->pageObject = $page;
		$this->pageObject->beforePage();
		$this->startPage();
		$this->pageObject->printContent($printResult);
		$this->pageObject->afterPage();
	}

	/**
	 * Gibt den Header der jetzigen Seite aus.
	 */
	public function Header() {
		$this->pageObject->printHeader();
	}

	/**
	 * Gibt den Footer der jetzigen Seite aus.
	 */
	public function Footer() {
		$this->pageObject->printFooter();
	}

	/**
	 * @return Page Gibt die jetzige Seite zurück.
	 */
	public function getPageObject() {
		return $this->pageObject;
	}
}
