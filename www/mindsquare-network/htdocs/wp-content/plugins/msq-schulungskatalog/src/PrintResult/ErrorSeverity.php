<?php


namespace Msq\Schulungskatalog\PrintResult;

/**
 * Eine Pseudo-Enumeration der erlaubten Fehler-Schweregrade.
 *
 * @package Msq\Schulungskatalog\PrintResult
 */
class ErrorSeverity {
	const NOTICE  = 0b0001;
	const WARNING = 0b0010;
	const FATAL   = 0b0100;
}
