<?php


namespace Msq\Schulungskatalog\Pages;


use DateInterval;
use DateTimeImmutable;

/**
 * Bietet eine Schnittsteller für Eigenschaften,
 * die zum Darstellen von Schulungsseiten benötigt werden.
 * @package Msq\Schulungskatalog\Pages
 */
interface SeminarSettingsInterface {
	/**
	 * @return string Der Titel der Schulung
	 */
	public function getTitle();

	/**
	 * @return string Das Ziel der Schulung
	 */
	public function getGoal();

	/**
	 * @return string Die Schulungsinhalte
	 */
	public function getTopics();

	/**
	 * @return float Der Preis der Schulung
	 */
	public function getPrice();

	/**
	 * @return float Der Inhouse-Preis der Schulung
	 */
	public function getInhousePrice();

	/**
	 * @return DateTimeImmutable[] Die Schulungstermine.
	 */
	public function getAppointments();

	/**
	 * @return DateInterval Die Dauer der Schulung.
	 */
	public function getDuration();

	/**
	 * @return string Die Uhrzeit, an welcher die Schulung stattfindet
	 */
	public function getTime();

	/**
	 * @return array Der Schulungsreferent als Array mit folgenden Feldern:
	 * 'image' => Bild-URL, 'name' => Name des Referenten
	 */
	public function getSpeaker();

	/**
	 * @return string Die Bild-URL des Schulungszertifikats
	 */
	public function getCertificate();
}
