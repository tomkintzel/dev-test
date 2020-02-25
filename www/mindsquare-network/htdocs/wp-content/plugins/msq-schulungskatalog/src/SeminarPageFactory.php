<?php


namespace Msq\Schulungskatalog;


use Msq\Schulungskatalog\AcfLoader\SeminarAcfLoader;
use Msq\Schulungskatalog\AcfLoader\SeminarCategoryAcfLoader;
use Msq\Schulungskatalog\Pages\CategoryTitlePage;
use Msq\Schulungskatalog\Pages\Page;
use Msq\Schulungskatalog\Pages\SeminarOverviewPage;
use Msq\Schulungskatalog\Pages\SeminarPage;
use WP_Post;
use WP_Term;
use function get_the_terms;
use function strcmp;
use function usort;

/**
 * Generiert (hauptsächlich) {@link SeminarPage}-Objekte anhand der übergebenen Seminare.
 *
 * @package Msq\Schulungskatalog
 * @see     SeminarPage
 * @see     SeminarOverviewPage
 * @see     CategoryTitlePage
 */
class SeminarPageFactory {
	protected $pageSettings;

	/**
	 * SeminarPageFactory constructor.
	 *
	 * @param MsqPageSettings $pageSettings
	 */
	public function __construct($pageSettings) {
		$this->pageSettings = $pageSettings;
	}

	/**
	 * Bereitet die übergebenen Schulungen für die Benutzung innerhalb dieser Klasse vor.
	 * Dafür werden sie ihren Kategorien untergeordnet.
	 * Die Kategorien sowie die Schulungen sind danach alphabetisch sortiert.
	 *
	 * @param WP_Post[] $seminars
	 *
	 * @return array
	 */
	private function prepareSeminars(array $seminars) {
		$terms        = [];
		$termSeminars = [];

		foreach ($seminars as $seminar) {
			$seminarTerms = get_the_terms($seminar, 'seminarkategorie');
			/** @var WP_Term $term */
			$term = reset($seminarTerms);

			if ($term === false) {
				continue;
			}

			if (!isset($terms[$term->term_id])) {
				$terms[$term->term_id] = $term;
			}

			$termSeminars[$term->term_id][] = $seminar;
		}

		uasort($terms, function (WP_Term $a, WP_Term $b) {
			return strcmp($a->name, $b->name);
		});

		$termIds   = array_keys($terms);
		$termCount = count($termIds);

		for ($i = 0; $i < $termCount; $i++) {
			$id = $termIds[$i];

			usort($termSeminars[$id], function (WP_Post $a, WP_Post $b) {
				return strcmp($a->post_title, $b->post_title);
			});
		}

		$sortedSeminars = [
			'terms'    => $terms,
			'seminars' => $termSeminars,
		];

		return $sortedSeminars;
	}

	/**
	 * Erstellt die Seiten für die übergebenen Schulungen.
	 *
	 * @param WP_Post[] $seminars
	 *
	 * @return Page[] Die erstellten Seiten
	 */
	public function createPages(array $seminars) {
		$preparedSeminars = $this->prepareSeminars($seminars);

		$overviewPage = new SeminarOverviewPage($preparedSeminars, $this->pageSettings);

		$pages               = [$overviewPage];
		$seminarCategoryACFL = new SeminarCategoryAcfLoader(SchulungskatalogPDF::ACF_PREFIX);

		/**
		 * @var int     $id
		 * @var WP_Term $term
		 */
		foreach ($preparedSeminars['terms'] as $id => $term) {
			$seminarCategoryACFL->setObject($term);
			$backgroundImage = $seminarCategoryACFL->getBackground();
			$headerImage     = $seminarCategoryACFL->getHeader();

			$pages[] = new CategoryTitlePage($term->name, $this->pageSettings, $backgroundImage);

			foreach ($preparedSeminars['seminars'][$id] as $seminar) {
				$seminarACFL = new SeminarAcfLoader('', $seminar);
				$pages[]     = new SeminarPage($seminarACFL, $this->pageSettings, $headerImage);
			}
		}

		return $pages;
	}
}
