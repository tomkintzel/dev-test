<?php


namespace Msq\Schulungskatalog;


use Closure;
use function apply_filters;
use function wp_normalize_path;

/**
 * Hilfs-Klasse, um URLs und Dateipfade im WordPress-System zu speichern
 * @package Msq\Schulungskatalog
 */
class WPLocation {
	/** @var string */
	private $baseUrl;
	/** @var string */
	private $baseDir;
	/** @var string */
	private $hookName;
	/** @var Closure */
	private $fileNameClosure;

	/**
	 * Erstellt eine WPLocation mit den übergebenen Parametern
	 *
	 * @param string  $baseUrl         Die Root-URL
	 * @param string  $baseDir         Das Root-Verzeichnis
	 * @param string  $hookName        Der Name des Filter-Hooks, mit dem die Pfade modifiziert werden können.
	 * @param Closure $fileNameClosure Eine Funktion, welche einen Dateinamen zurückgibt.
	 */
	public function __construct($baseUrl, $baseDir, $hookName, Closure $fileNameClosure) {
		$this->setBaseUrl($baseUrl);
		$this->setBaseDir($baseDir);
		$this->setHookName($hookName);
		$this->setFileNameClosure($fileNameClosure);
	}

	public function getUrl(...$fileNameParameters) {
		return $this->getLocation('url', $this->baseUrl, ...$fileNameParameters);
	}

	public function getPath(...$fileNameParameters) {
		return $this->getLocation('path', $this->baseDir, ...$fileNameParameters);
	}

	private function getLocation($hookSuffix, $baseLocation, ...$fileNameParameters) {
		$fileName = $this->fileNameClosure->call($this, ...$fileNameParameters);

		$location = $baseLocation;

		if (substr($baseLocation, -1) !== '/' && substr($fileName, 0) !== '/') {
			$location .= '/';
		}

		$location .= $fileName;

		apply_filters($this->hookName . $hookSuffix, $location, $baseLocation, $fileName, $fileNameParameters);

		return $location;
	}

	/**
	 * @return string
	 */
	public function getBaseUrl() {
		return $this->baseUrl;
	}

	/**
	 * @param string $baseUrl
	 */
	public function setBaseUrl($baseUrl) {
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @return string
	 */
	public function getBaseDir() {
		return $this->baseDir;
	}

	/**
	 * @param string $baseDir
	 */
	public function setBaseDir($baseDir) {
		$baseDir       = wp_normalize_path($baseDir);
		$this->baseDir = $baseDir;
	}

	/**
	 * @return string
	 */
	public function getHookName() {
		return $this->hookName;
	}

	/**
	 * @param string $hookName
	 */
	public function setHookName($hookName) {
		$this->hookName = $hookName;
	}

	/**
	 * @return Closure
	 */
	public function getFileNameClosure() {
		return $this->fileNameClosure;
	}

	/**
	 * @param Closure $fileNameClosure
	 */
	public function setFileNameClosure($fileNameClosure) {
		$this->fileNameClosure = $fileNameClosure;
	}
}
