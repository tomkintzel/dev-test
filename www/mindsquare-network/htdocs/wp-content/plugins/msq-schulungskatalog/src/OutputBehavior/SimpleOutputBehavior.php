<?php


namespace Msq\Schulungskatalog\OutputBehavior;


use Msq\Schulungskatalog\PDF;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use function call_user_func_array;

/**
 * Die einfachste Möglichkeit, Inhalte auszugeben.
 * Es wird nur ein Hauptinhalt ausgegeben.
 *
 * @package Msq\Schulungskatalog\OutputBehavior
 */
class SimpleOutputBehavior implements OutputBehaviorInterface {
	/** @var callable */
	protected $mainContentCallback;

	public function __construct(callable $mainContentCallback) {
		$this->mainContentCallback = $mainContentCallback;
	}

	public function output(PDF $tcpdf, PrintResult $printResult) {
		call_user_func_array($this->mainContentCallback, [$tcpdf, $printResult]);
	}

	/**
	 * @return callable
	 */
	public function getMainContentCallback() {
		return $this->mainContentCallback;
	}

	/**
	 * @param callable $mainContentCallback
	 */
	public function setMainContentCallback($mainContentCallback) {
		$this->mainContentCallback = $mainContentCallback;
	}
}
