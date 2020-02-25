<?php


namespace Msq\Schulungskatalog;

use Aws\Common\Enum\Time;
use DateTime;
use Msq\Schulungskatalog\PrintResult\ErrorSeverity;
use Msq\Schulungskatalog\PrintResult\PrintResult;
use WP_Post;
use function add_action;
use function add_filter;
use function apply_filters;
use function delete_transient;
use function do_action;
use function esc_attr;
use function get_current_screen;
use function get_field_object;
use function get_post;
use function get_term;
use function get_transient;
use function plugin_dir_url;
use const MSQ_SCHULUNGSKATALOG_DIR;
use const MSQ_SCHULUNGSKATALOG_FILE;

/**
 * Verwaltet die Interaktion zwischen WordPress und der {@link SchulungskatalogPDF}-Klasse.
 *
 * @package Msq\Schulungskatalog
 */
class Schulungskatalog {
	private $baseUrl;
	private $basePath;
	private $timestampStart;

	const TRANSIENT_PREFIX = 'msqsc_print_result_';

	public function __construct() {
		add_action('wp_async_msqsc_print_catalog', [$this, 'printCatalog']);
		add_action('acf/save_post', [$this, 'handleSettingsEdit']);
		add_action('save_post', [$this, 'handlePostEdit']);
		add_action('admin_notices', [$this, 'showNotices']);
		add_filter('wp_update_term_data', [$this, 'handleTermEdit'], 10, 4);
		add_filter('msqsc/filter/seminars', [$this, 'seminarFilter']);

		$this->baseUrl  = plugin_dir_url(MSQ_SCHULUNGSKATALOG_FILE);
		$this->basePath = MSQ_SCHULUNGSKATALOG_DIR;

		$this->catalogLocation = new WPLocation(
			$this->baseUrl,
			$this->basePath,
			'msqsc/filter/catalog',
			function () {
				return 'schulungskatalog.pdf';
			}
		);

		$this->previewLocation = new WPLocation(
			$this->baseUrl,
			$this->basePath,
			'msqsc/filter/preview/',
			function (WP_Post $post) {
				return 'previews/' . $post->ID . '.pdf';
			}
		);

		$this->timestampStart = 0;
	}

	/**
	 * @param WP_Post[] $seminars
	 *
	 * @return WP_Post[]
	 */
	public function seminarFilter(array $seminars) {
		$seminars = array_filter($seminars, function ($seminar) {
			$appointments = get_field('seminartermine', $seminar);
			$printSeminar = false;

			if ($appointments !== false) {
				foreach ($appointments as $appointment) {
					$appointmentDate = new DateTime($appointment['von_datum']);
					$date            = new DateTime();
					$printSeminar    = $date < $appointmentDate;
				}
			}

			return $printSeminar;
		});

		return $seminars;
	}

	public function handleSettingsEdit() {
		$screen = get_current_screen();

		if ($screen->id !== 'seminare_page_acf-options-schulungsseiten') {
			return;
		}

		do_action('msqsc_print_catalog');
	}

	public function handlePostEdit($postId) {
		$post = get_post($postId);

		if ($post->post_status !== 'publish' || $post->post_type !== 'seminare') {
			return;
		}

		$seminars = apply_filters('msqsc/filter/seminars', [$post]);

		if (empty($seminars)) {
			return;
		}

		$seminar = reset($seminars);
		$pdf     = new SchulungskatalogPDF();
		$pdf->addSeminar($seminar);
		$result = $pdf->printCatalog();
		$pdf->Output(MSQ_SCHULUNGSKATALOG_DIR . '/previews/' . $seminar->ID . '.pdf', 'F');

		set_transient($this->getTransientName($post), $result, 300);
	}

	public function showNotices() {
		$currentScreen = get_current_screen();

		if (
			$currentScreen === null
			|| $currentScreen->post_type !== 'seminare'
			|| $currentScreen->base !== 'post'
		) {
			return;
		}


		$post = get_post();
		/** @var PrintResult $result */
		$result = get_transient($this->getTransientName($post));

		if ($result === false) {
			return;
		}

		$errors    = $result->getErrors();

		if (count($errors) > 0) :
			$url = $this->previewLocation->getUrl($post);
			$class = $this->getClassForSeverity($result->getStatus());
			?>
			<div class="notice <?php echo $class; ?>">
				<p>
					Im Schulungskatalog könnte diese Schulung fehlerhaft aussehen.
					<a href="<?php echo $url; ?>">Vorschau ansehen</a>
				</p>
			</div>
		<?php else:
			$url = $this->catalogLocation->getUrl();
			delete_transient($this->getTransientName($post));

			$date = new DateTime();
			$this->timestampStart = $date->getTimestamp(); 

			do_action('msqsc_print_catalog');
			?>
			<style>
				.spinner-border {
					display: inline-block;
					width: 10px;
					height: 10px;
					vertical-align: text-bottom;
					border: 0.2em solid currentColor;
					border-right-color: transparent;
					border-radius: 50%;
					-webkit-animation: spinner-border .75s linear infinite;
					animation: spinner-border .75s linear infinite;
				}
				.sr-only {
					position: absolute;
					width: 1px;
					height: 1px;
					padding: 0;
					overflow: hidden;
					clip: rect(0,0,0,0);
					white-space: nowrap;
					border: 0;
				}
				@keyframes spinner-border{
					from {
						transform: rotateZ(0);
					}
					to {
						transform: rotateZ(360deg);
					}
				}
			</style>
			<div id="pdf-notice" class="notice notice-warning is-dismissible">
				<p>
					<span id="fileStatusText">Schulungskatalog wird aktualisiert. Dies kann etwas Zeit in Anspruch nehmen.</span>
					<a id="fileStatusLink" class="button button-small disabled" href="javascript:void(0);">
						Bitte warten... 
						<span class="spinner-border" role="status">
							<span class="sr-only">Datei wird erstellt</span>
						</span>
					</a>
				</p>
			</div>
			<script type="text/javascript">
				var $statusLink = $('#fileStatusLink');
				var $statusText = $('#fileStatusText');
				var $notice = $('#pdf-notice');
				var timeVar; 
				var timestampStart = <?php echo $this->timestampStart; ?>;
				var pdfUrl = "<?php echo $url; ?>";
				/**
				 * Converts the XMLHttp Header "last-modified" to UnixTimestamp
				 * 
				 * @return number The Unix Timecode
				 */
				function getUnixTimecode(input){
					var timecode = Date.parse(input);
					return  timecode;
				}
				/**
				 * XMLHttp request to get the Last-Modified header for comparison
				 *
				 * @return void
				 */
				function checkFileChanged(){
					var xhr = $.ajax({
						url: "<?php echo $this->baseUrl; ?>/schulungskatalog.pdf", 
						success: function(response) {
							$timeCode = xhr.getResponseHeader("Last-Modified");
							$timeCode = getUnixTimecode($timeCode);
							timeVar = new Date($timeCode);
						},
						error: function(response){
							return false;
						}
					});
					return true;
				}
				/**
				 * Debug output
				 *
				 * @return void
				 */
				function debugTimeDifference(){
					if(timeVar){
						console.log( "time-difference: "+ (timestampStart - timeVar.getTime()/1000) );
					}
				}
				/**
				 * Loop checker for pdf updates with visual representation
				 */
				function checkLoop (counter) {
					setTimeout(function () {
						var response = checkFileChanged();
						if (!response){
							$notice.removeClass("notice-warning");
							$notice.addClass("notice-error");
							$statusLink.text("Hier ansehen");
							$statusLink.removeClass("disabled");
							$statusLink.attr("href", pdfUrl);
							$statusText.text("Status des Schulungskatalogs konnte nicht erfasst werden.");
							counter = 0;
							return;
						}
						//debugTimeDifference();
						if(timeVar && timeVar.getTime()/1000 > timestampStart){
							$notice.removeClass("notice-warning");
							$notice.addClass("notice-success");
							$statusLink.text("Hier ansehen");
							$statusLink.removeClass("disabled");
							$statusLink.attr("href", pdfUrl);
							$statusText.text("Schulungskatalog wurde erfolgreich aktualisiert!");
							counter = 0;
						}
						if (--counter > 0) {          // If counter > 0, keep going
							checkLoop(counter);       // Call the loop again, and pass it the current value of i
						}
					}, 1000);
				}
				checkLoop(70); //check for max 70 seconds
			</script>
		<?php
		endif;

		foreach ($errors as $error) {
			$classes = 'notice ' . $this->getClassForSeverity($error->getSeverity());

			printf(
				'<div class="%2$s"><p>%1$s</p></div>',
				esc_html($error->getMessage()),
				esc_attr($classes)
			);
		}
	}

	private function getClassForSeverity($severity) {
		if ($severity > ErrorSeverity::WARNING) {
			return 'notice-error';
		} else {
			return 'notice-warning';
		}
	}

	public function handleTermEdit($data, $termId, $taxonomy, $args) {
		if ($taxonomy !== 'seminarkategorie') {
			return $data;
		}

		$term    = get_term($termId);
		$changed = $term->name !== $data['name'];

		if (!$changed) {
			$catalogFields = [
				get_field_object('msqsc_background', $term, false),
				get_field_object('msqsc_header', $term, false)
			];

			foreach ($catalogFields as $catalogField) {
				$newValue = $args['acf'][$catalogField['key']];
				$oldValue = $catalogField['value'];

				$changed = $newValue !== $oldValue;
				if ($changed) {
					break;
				}
			}
		}

		if ($changed) {
			do_action('msqsc_print_catalog');
		}

		return $data;
	}

	public function printCatalog() {
		$seminars = get_posts([
			'post_type'   => 'seminare',
			'post_status' => 'publish',
			'nopaging'    => true,
		]);

		$seminars = apply_filters('msqsc/filter/seminars', $seminars);

		$pdf = new SchulungskatalogPDF();
		$pdf->setSeminars($seminars);
		$pdf->printCatalog();
		$location = $this->catalogLocation->getPath();
		$pdf->Output($location, 'F');
	}

	private function getTransientName(WP_Post $post) {
		return self::TRANSIENT_PREFIX . $post->ID;
	}
}
