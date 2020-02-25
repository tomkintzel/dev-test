<?php


namespace Msq\Schulungskatalog;


use function is_array;
use function plugin_dir_url;
use const MSQ_SCHULUNGSKATALOG_FILE;

/**
 * Hilfs-Methoden, um Bilder zu laden.
 * @package Msq\Schulungskatalog
 */
trait AssetTrait {
	protected static $pluginDir;

	public function getAssetUrl($assets = null) {
		if (!isset(self::$pluginDir)) {
			self::$pluginDir = plugin_dir_url(MSQ_SCHULUNGSKATALOG_FILE);
		}

		$assetRoot = self::$pluginDir . 'assets/';
		$assetUrl  = $this->handleAssetUrl($assets, $assetRoot);

		return $assetUrl;
	}

	private function handleAssetUrl($assets, $assetRoot) {
		if (is_string($assets)) {
			$assetUrl = $assetRoot . $assets;
		} elseif (is_array($assets)) {
			$assetUrl = [];

			foreach ($assets as $asset) {
				$assetUrl[] = $this->handleAssetUrl($asset, $assetRoot);
			}
		} else {
			$assetUrl = $assetRoot;
		}

		return $assetUrl;
	}
}
