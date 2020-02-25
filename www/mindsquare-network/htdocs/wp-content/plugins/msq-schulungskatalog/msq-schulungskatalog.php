<?php
/**
 * Plugin Name: MSQ - Schulungskatalog
 * Description: Erstellt einen Schulungskatalog als PDF.
 * Version: 1.0
 * Author: mindsquare
 * Text Domain: msq-schulungskatalog
 * Domain Path: /languages
 */

use Msq\Schulungskatalog\SaveCatalogTask;
use Msq\Schulungskatalog\Schulungskatalog;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/assets/acf/schulungskatalog.php';

const MSQ_SCHULUNGSKATALOG_DIR      = __DIR__;
const MSQ_SCHULUNGSKATALOG_FILE     = __FILE__;
const MSQ_SCHULUNGSKATALOG_FONT_DIR = MSQ_SCHULUNGSKATALOG_DIR . '/assets/fonts';


add_filter('https_local_ssl_verify', 'msqsc_local_debug_ssl');

function msqsc_local_debug_ssl($ssl) {
	return $ssl && !WP_DEBUG;
}


add_action('plugins_loaded', 'msqsc_init');

function msqsc_init() {
	global $schulungskatalog;

	$schulungskatalog = new Schulungskatalog();
	$saveCatalogTask  = new SaveCatalogTask();
}
