<?php


namespace Msq\Schulungskatalog\Pages;

use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\TcpdfFontHandler;

/**
 * Eine Schnittstelle für übliche Parameter von Pages.
 *
 * @package Msq\Schulungskatalog\Pages
 */
interface PageSettingsInterface {
	/**
	 * @return PDF Die PDF-Instanz, mit welcher die Seite ausgegeben werden soll.
	 */
	public function getPdf();

	/**
	 * @return TcpdfFontHandler Welcher FontHandler für die Seite verwendet werden soll.
	 */
	public function getFontHandler();
}
