<?php
/*
 * Plugin Name: MSQ - Download-Einbettung
 * Plugin URI: https://mindsquare.de
 * Description: Ermöglicht das Einbetten von Downloads
 * Version: 0.1
 * Author: Stefan Wiebe
 */

const DOWNLOAD_EMBED_PLUGIN_DIR = __DIR__;
const DOWNLOAD_EMBED_PLUGIN_FILE = __FILE__;

require (DOWNLOAD_EMBED_PLUGIN_DIR . '/src/download-embeds-class.php');

MSQ_Download_Embeds::getInstance();