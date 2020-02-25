<?php


namespace Msq\Schulungskatalog\PrintResult;

/**
 * Ein Fehler während der Ausgabe.
 *
 * @package Msq\Schulungskatalog\PrintResult
 */
class PrintError {
	/** @var int */
	private $severity;

	/** @var string */
	private $message;

	/** @var int */
	private $page;

	/**
	 * Konstruktor.
	 *
	 * @param int    $severity Die Fehlerschwere, siehe {@link ErrorSeverity}
	 * @param string $message  Die Fehlermeldung
	 * @param int    $page     Die Seite, auf welcher der Fehler auftrat
	 */
	public function __construct($severity, $message, $page = -1) {
		$this->severity = $severity;
		$this->message  = $message;
		$this->page     = $page;
	}

	/**
	 * @return int
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * @param int $severity
	 */
	public function setSeverity($severity) {
		$this->severity = $severity;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @return int
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @param int $page
	 */
	public function setPage($page) {
		$this->page = $page;
	}
}
