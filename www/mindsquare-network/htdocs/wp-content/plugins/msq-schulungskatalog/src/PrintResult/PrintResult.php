<?php


namespace Msq\Schulungskatalog\PrintResult;


use function array_reduce;

/**
 * Das Resultat einer PDF-Ausgabe.
 *
 * @package Msq\Schulungskatalog\PrintResult
 */
class PrintResult {
	/** @var PrintError[] */
	private $errors;

	/**
	 * PrintResult constructor.
	 */
	public function __construct() {
		$this->errors = [];
	}

	/**
	 * Erzeugt eine Bitmaske anhand aller Fehler während der Ausgabe.
	 * @return mixed|PrintError
	 */
	public function getStatus() {
		$status = array_reduce($this->errors, function (PrintError $last = null, PrintError $current = null) {
			$lastSeverity = $last === null ? 0 : $last->getSeverity();

			return $lastSeverity | $current->getSeverity();
		});

		return $status;
	}

	/**
	 * @return PrintError[]
	 */
	public function getErrors() {
		return $this->errors;
	}

	public function addError(PrintError $error) {
		$this->errors[] = $error;
	}
}
