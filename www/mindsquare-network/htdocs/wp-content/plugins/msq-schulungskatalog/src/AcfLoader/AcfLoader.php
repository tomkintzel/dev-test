<?php


namespace Msq\Schulungskatalog\AcfLoader;


use WP_Post;
use function apply_filters;
use function get_field;
use function str_replace;
use function strip_shortcodes;

/**
 * Eine Klasse, um zentral ACF-Felder für einen gemeinsamen Zweck zu laden.
 * @package Msq\Schulungskatalog\AcfLoader
 */
class AcfLoader {
	/** @var string */
	protected $prefix;
	/** @var WP_Post|string */
	protected $object;

	const LINE_BREAK_CHARACTERS = ["\r\n", "\n"];

	/**
	 * AcfLoader constructor.
	 *
	 * @param string         $prefix Das Prefix der ACF-Felder
	 * @param WP_Post|string $object Aus welchem Objekt die Felder geladen werden sollten
	 */
	public function __construct($prefix, $object = null) {
		$this->prefix = $prefix;
		$this->object = $object;
	}

	public function getContentField($name) {
		$content = $this->getField($name, false);
		$content = $this->stripImagesAndShortcodes($content);
		$content = apply_filters('the_content', $content);
		return $content;
	}

	public function getField($name, $formatValue = true) {
		return get_field($this->prefix . $name, $this->object, $formatValue);
	}

	public function getOptionField( $name, $formatValue = true ) {
		return get_field( $name, 'option', $formatValue );
	}

	/**
	 * Ersetzt Zeilenumbruch-Charaktere innerhalb des Textes mit <br/>-Tags.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	protected function replaceLinebreaks($text) {
		return str_replace(self::LINE_BREAK_CHARACTERS, '<br/>', $text);
	}

	protected function stripImagesAndShortcodes($text) {
		$newText = strip_shortcodes($text);
		$newText = preg_replace('/<img[^>]+\>/i', '', $newText);

		return $newText;
	}

	/**
	 * @return string|WP_Post
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @param string|WP_Post $object
	 */
	public function setObject($object) {
		$this->object = $object;
	}
}
