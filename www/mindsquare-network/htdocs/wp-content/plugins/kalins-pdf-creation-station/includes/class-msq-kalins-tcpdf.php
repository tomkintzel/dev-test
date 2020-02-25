<?php

/**
 * Diese Datei erstellt eine neue Klasse namens MSQ_Kalins_TCPDF, mit der
 * von diesen Plugin 'kalins-pdf-creation-station' verwendeten TCPDF Befehle
 * zur Verfügung stellt.
 * Neben eigenen Funktionen werden einige Funktionen von der Klasse TCPDF
 * überschrieben. So zum Beispiel die Header und die Footer Funktion, damit
 * diese von einem Template geladen werden können.
 */
class MSQ_Kalins_TCPDF extends TCPDF {
	/**
	 * @todo Auf diese Variable soll anders zugegriffen werden können.
	 * @todo Beschreibung hinzufügen.
	 */
	public $post;

	/**
	 * @var array<string> Diese Variable speichert den aktuellen Slug einer
	 * Seite ab. Zum Beispiel $slug = array( 'post', 'test' ); // Hierbei
	 * ist post der Post-Type und test der Post-Slug.
	 */
	public $slug = array();

	/**
	 * @var string Den aktuellen Pfad vom Header-Template. Diese Variable
	 * wurde für die Optimierung hinzugefügt, mit dem mehrfache
	 * Funktionsaufrufe erspart werden.
	 */
	protected $headerFilename;

	/**
	 * @var string Den aktuellen Pfad vom Footer-Template. Diese Variable
	 * wurde für die Optimierung hinzugefügt, mit dem mehrfache
	 * Funktionsaufrufe erspart werden.
	 */
	protected $footerFilename;

	/**
	 * Diese Funktion gibt den möglichst längsten Dateinamen zurück, der
	 * im Template-Ordner gefunden wurde. Dabei werden absteigend alle
	 * Kombinationen mit templateName und dem slug probiert. wenn keine
	 * passende Kombination gefunden wurde, wird null zurückgegeben.
	 *
	 * Beispiel:
	 *   $slug = array( 'post', 'test' );
	 *   LocateTemplate( 'header' ) ->
	 *   1. header-post-test.php
	 *   2. header-post.php
	 *   3. header.php
	 *   4. null
	 *
	 * @param string $templateName Gibt an welche Datei geladen werden soll.
	 *
	 * @return string|null Gibt zurück, welche passende Datei gefunden wurde.
	 *
	 * @todo Die Benutzereingaben prüfen.
	 * 1. $templateName muss ein String sein
	 * 2. $templateName muss ein Dateiname sein, ohne den Template-Ordner zu
	 * verlassen.
	 */
	public function LocateTemplate( $templateName ) {
		// Probiere alle Kombinationen vom Slug
		for ( $slugLength = count( $this->slug ); $slugLength > 0; $slugLength -- ) {
			$filename = MSQ_KALINS_TCPDF_TEMPLATES_PATH . $templateName . '-' . implode( '-',
					array_slice( $this->slug, 0, $slugLength ) ) . '.php';
			if ( file_exists( $filename ) ) {
				return $filename;
			}
		}
		// Ohne Slug probieren
		$filename = MSQ_KALINS_TCPDF_TEMPLATES_PATH . $templateName . '.php';
		if ( file_exists( $filename ) ) {
			return $filename;
		}

		// Keine passende Datei gefunden
		return null;
	}

	/**
	 * Diese Funktion erstellt zum Objekt die passenden Seiten.
	 *
	 * @param string $type Der Type des WP_Post Objektes
	 * @param array  $slug Den Slug in einzelteile zerlegt. Zum Beispiel
	 *                     (von: single-post-slug =>
	 *                     LoadTemplate('single', array('post', 'slug')))
	 *
	 * @todo Weitere Variablen übergeben können, ohne das diese stendig in
	 *       das PDF Objekt hinzugefügt werden müssen, zum Beispiel durch ein
	 *       zusätzlichen Paramter
	 *
	 */
	public function LoadTemplate( $type, $slug = array() ) {
		// Speichere den aktuellen Slug ab
		$currentSlug = $this->slug;

		// Ersetzte den aktuellen Slug
		$this->slug = $slug;

		// Optimierung für Header und Footer
		$this->headerFilename = $this->LocateTemplate( 'pdf-page-header' );
		$this->footerFilename = $this->LocateTemplate( 'pdf-page-footer' );

		// Lade das Template
		$templateName = $this->LocateTemplate( $type );
		if ( ! empty( $templateName ) ) {
			require( $templateName );
		} else {
			/** @todo Eine bessere Fehlerbehandlung */
			trigger_error( 'Die Datei "' . $templateName . '" existiert nicht.' );
		}

		// Den letzten Slug wiederherstellen
		$this->slug = $currentSlug;
	}

	/**
	 * Deise Funktion führt eine weitere Template-Datei aus.
	 *
	 * @param string $partName Der Name der Template-Datei
	 *
	 * @return boolean Ob die Datei geladen wurde
	 */
	public function LoadTemplatePart( $partName ) {
		$partName = $this->LocateTemplate( $partName );
		if ( ! empty( $partName ) ) {
			require( $partName );

			return true;
		} else {
			/** @todo Eine bessere Fehlermeldung */
			return false;
		}
	}

	/**
	 * Diese Funktion wird beim zeichnen des Headers verwendet.
	 *
	 * @override
	 */
	public function Header() {
		include( $this->headerFilename );
	}

	/**
	 * Diese Funktion wird beim zeichnen des Footers verwendet.
	 *
	 * @override
	 */
	public function Footer() {
		include( $this->footerFilename );
	}
}

?>
