<?php


namespace Msq\Schulungskatalog\PdfElement;


use Msq\Schulungskatalog\PDF;

/**
 * Ein vermutlich überflüssiges Interface. Naja, egal.
 * @package Msq\Schulungskatalog\PdfElement
 */
interface PdfElementInterface {
	public function output(PDF $pdf);
}
