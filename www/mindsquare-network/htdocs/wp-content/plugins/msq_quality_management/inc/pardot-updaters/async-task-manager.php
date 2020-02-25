<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

use MSQ\Plugin\Quality_Management\Quality_Management;
use WP_Async_Task;

class Async_Task_Manager extends WP_Async_Task {
	/** @var int STATUS_WAITING */
	public const STATUS_WAITING = 0x00;

	/** @var int STATUS_PREPARING */
	public const STATUS_PREPARING = 0x01;

	/** @var int STATUS_RUNNING */
	public const STATUS_RUNNING = 0x02;

	/** @var int STATUS_CANCELED */
	public const STATUS_CANCELED = 0x03;

	/** @var int STATUS_FAIL */
	public const STATUS_FAILED = 0x04;

	/** @var int MIN_EXECUTION_TIME */
	public const MIN_EXECUTION_TIME = 100;

	/** @var int $id */
	private $id;

	/** @var string[] $task_runners */
	private $task_runners;

	/** @var mixed[] $tasks */
	private $tasks = [];

	/** @var int $status */
	private $status = self::STATUS_WAITING;

	/** @var int $last_activity */
	private $last_activity = 0;

	/** */
	public function __construct( $action ) {
		// vars
		$this->action = $action;

		// init
		$this->load();

		// hooks
		add_action( sprintf( 'admin_post_wp_async_%s', $this->action ), [ $this, 'handle_postback' ] );
		add_action( 'wp_ajax_get-async-task-manager-progress', [ $this, 'get_progress' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/** */
	private function load() {
		$settings = get_option( $this->action );
		$this->id = $settings[ 'id' ] ?? null;
		$this->tasks = $settings[ 'tasks' ] ?? [];
		$this->status = $settings[ 'status' ] ?? self::STATUS_WAITING;
		$this->last_activity = $settings[ 'last_activity' ] ?? 0;
	}

	/** */
	public function save() {
		$settings = [
			'id' => $this->id,
			'tasks' => $this->tasks,
			'status' => $this->status,
			'last_activity' => $this->last_activity
		];
		update_option( $this->action, $settings );
	}

	/** */
	public function reset() {
		$this->status = self::STATUS_WAITING;
		$this->save();
	}

	/** */
	public function cancel() {
		$this->status = self::STATUS_CANCELED;
		$this->save();
	}

	/** */
	public function enqueue_scripts() {
		// Das Skript für die Progress-Bar wird nur gebraucht, wenn der Updater gerade ausgeführt wird
		if( $this->is_locked() ) {
			$quality_management = Quality_Management::get_instance();
			$url = $quality_management->get_url();
			$path = $quality_management->get_path();
			$filename = "{$url}assets/js/async-task-manager.js";
			$version = filemtime( "{$path}assets/js/async-task-manager.js" );

			wp_enqueue_script( 'msq-async-task-manager-script', $filename, [], $version );
		}
	}

	/**
	 * @param int $priority
	 * @param string $class_name
	 */
	public function add_task_runner( $priority, $class_name ) {
		$this->task_runners[ $priority ] = $class_name;
	}

	/**
	 * @param string $class_name
	 * @param mixed $arg
	 */
	public function add_task( $class_name, $arg ) {
		if( empty( $this->tasks[ $class_name ][ $arg ] ) ) {
			$this->tasks[ $class_name ][ $arg ] = Async_Task::STATUS_WAITING;
		}
	}

	/**
	 * @see WP_Async_Task
	 * @var mixed $data
	 **/
	protected function prepare_data( $data ) {
		// Aktualisiere den Status
		$settings = get_option( $this->action );
		$this->status = $settings[ 'status' ] ?? self::STATUS_WAITING;
		$this->last_activity = $settings[ 'last_activity' ] ?? 0;

		// Wenn ein neuer Prozess gestartet werden kann
		if( !$this->is_locked() ) {
			// Erstelle eine neue Aufgabe
			$this->status = self::STATUS_PREPARING;
			$this->last_activity = microtime( true );
			$this->id = md5( uniqid( rand(), true ) );

			// Wenn ein neuer Auftrag erstellt wurde
			if( empty( $_POST ) ) {
				// Entferne alle erledigten Aufgaben
				foreach( $this->tasks as $class_name => $tasks ) {
					foreach( $tasks as $arg => $status ) {
						if( $status != Async_Task::STATUS_WAITING ) {
							unset( $this->tasks[ $class_name ][ $arg ] );
						}
					}
				}
			}

			// Wenn der Auftrag überschrieben werden soll
			$data[ 'id' ] = $this->id;
			$this->save();
		}
		return $data;
	}

	/**
	 * Prüft ob der Updater gerade in einem blockierendem Prozess ist.
	 * @return boolean
	 */
	public function is_locked() {
		// Prüfe den Prozess-Status
		if( !in_array( $this->status, [ self::STATUS_PREPARING, self::STATUS_RUNNING] ) ) {
			return false;
		}

		// Prüfe die ID
		if( isset( $_POST[ 'id' ] ) && $this->id == $_POST[ 'id' ] ) {
			return false;
		}

		// Prüfe ob ein Timeout-Event aufgetreten ist
		$max_execution_time = max( ini_get( 'max_execution_time' ), self::MIN_EXECUTION_TIME );
		if( microtime( true ) - $this->last_activity > 2 * $max_execution_time ) {
			$this->status = self::STATUS_FAILED;
			$this->save();
			return false;
		}
		return true;
	}

	/**
	 * @see WP_Async_Task
	 */
	protected function run_action() {
		// Kann ein neuer Prozess gestartet werden?
		if( $this->is_locked() ) {
			return false;
		}

		// PHP-Einstellungen anpassen
		ini_set( 'pcre.backtrack_limit', '23001337' );
		ini_set( 'pcre.recursion_limit', '23001337' );

		// Sortiere die Aufgaben
		ksort( $this->task_runners );

		// Setzte den Status auf running
		$this->status = self::STATUS_RUNNING;
		$this->last_activity = microtime( true );
		$this->save();

		// Bearbeite alle Aufgaben
		$max_execution_time = max( ini_get( 'max_execution_time' ), self::MIN_EXECUTION_TIME );
		$start_time =  $_SERVER [ 'REQUEST_TIME_FLOAT' ];
		foreach( $this->task_runners as $class_name ) {
			if( !empty( $this->tasks[ $class_name ] ) ) {
				$waiting_tasks = [];
				foreach( $this->tasks[ $class_name ] as $arg => $status ) {
					if( $status == Async_Task::STATUS_WAITING ) {
						$waiting_tasks[] = $arg;
					}
				}
				if( !empty( $waiting_tasks ) ) {
					$task_runner = new $class_name();
					foreach( $waiting_tasks as $arg ) {
						// Prüfe ob die Execution-Time überscrhitten wurde
						$elapsed_time = $this->last_activity - $start_time;
						$this->last_activity = microtime( true );
						$this->save();
						if( $elapsed_time > $max_execution_time / 2 ) {
							// Timeout
							$this->launch();
							exit();
						}

						// Ändere den Status
						$this->tasks[ $class_name ][ $arg ] = Async_Task::STATUS_RUNNING;
						$this->save();

						// Führe den Task aus
						$task_runner->execute( $arg );
						$this->tasks[ $class_name ][ $arg ] = Async_Task::STATUS_DONE;
						$this->save();
					}
				}
			}
		}

		// Soll der Updater eine neue Runde starten?
		$this->last_activity = microtime( true );
		$count_tasks_status = $this->count_tasks_status();
		if( !empty( $count_tasks_status[ Async_Task::STATUS_WAITING ] ) ) {
			$this->save();
			$this->launch();
		} else {
			$this->status = self::STATUS_WAITING;
			$this->save();
		}
	}

	/**
	 * @see WP_Async_Task::launch_on_shutdown()
	 */
	public function launch_on_shutdown() {
		if ( ! empty( $this->_body_data ) ) {
			$cookies = array();
			$striped_cookies = array_map( 'stripslashes_deep', $_COOKIE );
			foreach ( $striped_cookies as $name => $value ) {
				$cookies[] = "$name=" . urlencode( is_array( $value ) ? serialize( $value ) : $value );
			}

			$request_args = array(
				'timeout'   => 0.1,
				'blocking'  => false,
				'sslverify' => false,
				'body'      => $this->_body_data,
				'headers'   => array(
					'cookie' => implode( '; ', $cookies ),
				),
			);

			$url = admin_url( 'admin-post.php' );

			wp_remote_post( $url, $request_args );
		}
	}

	/**
	 * @return int
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * @return int[]
	 */
	public function count_tasks_status() {
		$count = [
			Async_Task::STATUS_WAITING => 0,
			Async_Task::STATUS_RUNNING => 0,
			Async_Task::STATUS_DONE => 0
		];
		foreach( $this->tasks as $tasks ) {
			foreach( $tasks as $status ) {
				$count[ $status ]++;
			}
		}
		return $count;
	}

	/** */
	public function display() {
		// vars
		if( $this->is_locked() ) {
			$count = $this->count_tasks_status();
			$open = $count[ Async_Task::STATUS_WAITING ] ?? 0;
			$running = $count[ Async_Task::STATUS_RUNNING ] ?? 0;
			$done = $count[ Async_Task::STATUS_DONE ] ?? 0;
			$progress_now = $done;
			$progress_max = $open + $running + $done;
			if( $progress_max > 0 ) {
				$progress_percentage = ceil( 100.0 * $progress_now / $progress_max );
				$progress = sprintf( '%s %% (%s / %s)', $progress_percentage, $progress_now, $progress_max );
				?>
					<div class="QMProgress">
						<div class="QMProgress-Bar">
							<div class="QMProgress-Progress QMProgress-Progress-striped QMProgress-Progress-animated" style="width: <?php echo $progress_percentage; ?>%;"></div>
							<div class="QMProgress-Info"><?php echo $progress; ?></div>
						</div>
						<span>Bitte warten Sie, während die Daten aktualisiert werden. Diese Aktion kann einige Minuten dauern.</span>
					</div>
				<?php
			}
		}
	}

	/** */
	public function get_progress() {
		$count = $this->count_tasks_status();
		$data = [
			'open' => $count[ Async_Task::STATUS_WAITING ] ?? 0,
			'running' => $count[ Async_Task::STATUS_RUNNING ] ?? 0,
			'done' => $count[ Async_Task::STATUS_DONE ] ?? 0,
			'status' => $this->get_status()
		];
		wp_send_json( $data );
		wp_die();
	}
}
