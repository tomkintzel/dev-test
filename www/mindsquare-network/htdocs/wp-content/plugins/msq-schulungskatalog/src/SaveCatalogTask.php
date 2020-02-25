<?php


namespace Msq\Schulungskatalog;


use Exception;
use WP_Async_Task;

/**
 * Eine Klasse für die asynchrone Erstellung des Schulungskatalogs.
 * @package Msq\Schulungskatalog
 */
class SaveCatalogTask extends WP_Async_Task {
	protected $action = 'msqsc_print_catalog';

	/**
	 * Prepare any data to be passed to the asynchronous postback
	 *
	 * The array this function receives will be a numerically keyed array from
	 * func_get_args(). It is expected that you will return an associative array
	 * so that the $_POST values used in the asynchronous call will make sense.
	 *
	 * The array you send back may or may not have anything to do with the data
	 * passed into this method. It all depends on the implementation details and
	 * what data is needed in the asynchronous postback.
	 *
	 * Do not set values for 'action' or '_nonce', as those will get overwritten
	 * later in launch().
	 *
	 * @param array $data The raw data received by the launch method
	 *
	 * @return array The prepared data
	 * @throws Exception If the postback should not occur for any reason
	 *
	 */
	protected function prepare_data($data) {
		return $data;
	}

	/**
	 * Run the do_action function for the asynchronous postback.
	 *
	 * This method needs to fetch and sanitize any and all data from the $_POST
	 * superglobal and provide them to the do_action call.
	 *
	 * The action should be constructed as "wp_async_task_$this->action"
	 */
	protected function run_action() {
		do_action("wp_async_$this->action");
	}
}
