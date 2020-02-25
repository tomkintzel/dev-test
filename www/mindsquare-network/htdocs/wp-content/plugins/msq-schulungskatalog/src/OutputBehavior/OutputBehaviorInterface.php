<?php


namespace Msq\Schulungskatalog\OutputBehavior;


use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\PrintResult;

/**
 * Eine Schnittstelle, um verschiedene Arten zu beschreiben, wie Inhalte ausgegeben werden können.
 * @package Msq\Schulungskatalog\OutputBehavior
 */
interface OutputBehaviorInterface {
	public function output(PDF $tcpdf, PrintResult $printResult);
}
