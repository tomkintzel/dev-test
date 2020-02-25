<?php
/**
 * @wordpress-plugin
 * Plugin Name:     MSQ - Referenz-Einbettung
 * Plugin URI:      https://mindsquare.de
 * Description:     Fügt einen Shortcode und einen TinyMCE-Button hinzu, um Referenzen in Beiträge einzubinden.
 * Version:         1.0.0
 * Author:          Stefan Wiebe
 * Author URI:      https://gitlab.com/stefanwiebe
 */

if (!defined('WPINC')) {
    die;
}

const MSQ_REFERENCE_EMBEDS_VERSION = '1.0.0';
const MSQ_REFERENCE_EMBEDS_DIR = __DIR__;
const MSQ_REFERENCE_EMBEDS_FILE = __FILE__;

require 'src/MsqReferenceEmbeds.php';

$referenceEmbeds = new MsqReferenceEmbeds();
$referenceEmbeds->run();
