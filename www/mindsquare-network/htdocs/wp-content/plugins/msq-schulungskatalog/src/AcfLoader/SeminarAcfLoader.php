<?php


namespace Msq\Schulungskatalog\AcfLoader;


use DateInterval;
use DateTimeImmutable;
use Msq\Schulungskatalog\Pages\SeminarSettingsInterface;
use WP_Post;
use function array_filter;

/**
 * Lädt ACF-Felder für Schulungen.
 * @package Msq\Schulungskatalog\AcfLoader
 */
class SeminarAcfLoader extends AcfLoader implements SeminarSettingsInterface {
	/** @var WP_Post */
	protected $object;

	public function __construct($prefix, WP_Post $object = null) {
		parent::__construct($prefix, $object);
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->object->post_title;
	}

	public function getGoal() {
		$goal = $this->getContentField('ihr_ziel');
		return $goal;
	}

	public function getTopics() {
		$topics = $this->getContentField('schwerpunkte');
		return $topics;
	}

	public function getPrice() {
		$price = $this->getField('angebotspreis');

		if (empty($price)) {
			$price = $this->getField('preis');
		}
		if (empty($price)) {
			$price = $this->getOptionField('msqsc_default_price');
		}

		$price = $this->formatNumberString($price);
		return $price;
	}

	public function getInhousePrice() {
		$price = $this->getField('inhouse-angebotspreis');

		if (empty($price)) {
			$price = $this->getField('inhouse_preis');
		}
		if (empty($price)) {
			$price = $this->getOptionField('msqsc_default_inhouse_price');
		}

		$price = $this->formatNumberString($price);
		return $price;
	}

	/**
	 * @return DateTimeImmutable[]
	 */
	public function getAppointments() {
		$appointments = $this->getField('seminartermine');

		$appointments = array_map(function ($appointment) {
			return new DateTimeImmutable($appointment['von_datum']);
		}, $appointments);

		$currentDate  = new DateTimeImmutable();
		$appointments = array_filter(
			$appointments,
			function ($appointment) use ($currentDate) {
				return $currentDate < $appointment;
			}
		);

		return $appointments;
	}

	/**
	 * @return DateInterval
	 * @throws \Exception
	 */
	public function getDuration() {
		$durationInDays = $this->getField('e_learning_dauer');

		if (empty($durationInDays)) {
			$durationInDays = 1;
		}

		$duration = new DateInterval('P' . $durationInDays . 'D');

		return $duration;
	}

	public function getTime() {
		return $this->getField('uhrzeit_von_-_bis');
	}

	public function getSpeaker() {
		/** @var WP_Post $speakerPost */
		$speakerPost = $this->getField('referent');
		$image       = get_field('bild', $speakerPost);
		$position    = get_field('position_bei_mindsquare', $speakerPost);

		$speaker = [
			'name'     => $speakerPost->post_title,
			'image'    => $image['url'],
			'position' => $position
		];

		return $speaker;
	}

	public function getCertificate() {
		return $this->getField('certificate');
	}

	public function formatNumberString($numberString) {
		$numberString = preg_replace('/[^\d,]/', '', $numberString);
		$numberString = str_replace(',', '.', $numberString);
		$number       = floatval($numberString);

		return $number;
	}

}
