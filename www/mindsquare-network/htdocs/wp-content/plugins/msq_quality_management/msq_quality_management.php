<?php
/**
 * Plugin Name: MSQ - Qualitätssicherung
 * Plugin URI:  https://mindsquare.de/
 * Description: Dieses Plugin vereinfacht die Qualitätssicherung
 * Version:     1.0
 * Author:      Andrej Genschel
 */

if( !defined( 'WPINC' ) ) {
	die;
}

// Composer-Autoloader hinzufügen
require_once __DIR__ . '/vendor/autoload.php';

// Startet das Plugin
use MSQ\Plugin\Quality_Management\Quality_Management;
Quality_Management::get_instance();
