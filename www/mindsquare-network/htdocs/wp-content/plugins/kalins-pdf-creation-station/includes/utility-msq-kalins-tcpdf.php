<?php
/**
 * In dieser Datei werden Funktionen definiert, die von den Templates verewndet
 * werden können.
 */

/**
 * Diese Funktion verwandelt ein 6 Stelligen Hexwert in ein RGB Array.
 *
 * @param string $hex Ein 6 Stelliger Hex-Wert
 * @return array Ein Array mit 3 Zahlen für (Rot, Blau, Grün).
 */
function hex2rgb( $hex ) {
	$hex = str_replace( "#", "", $hex );

	if( strlen( $hex ) == 3 ) {
		$r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1) );
		$g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1) );
		$b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}
	$rgb = array( $r, $g, $b );
	return $rgb;
}

/**
 * Diese Funktion gibt ein Array mit allen Sass-Variablen von einer Datei
 * zurück.
 *
 * @param string $filename Den Pfad zur SASS-Datei
 * @return array Ein Array mit allen Varaiblen von der SASS-Datei
 */
function getSassVars( $filename ) {
	// Prüfe ob die Datei existiert
	if( file_exists( $filename ) ) {
		$sassContent = file_get_contents( $filename );
		$sassVars = array();

		// Suche alle Variablen heraus
		if( preg_match_all( '/\$([^:]+): *([^;!]+) *(!(default|important))?;/i', $sassContent, $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$sassVars[ $match[ 1 ] ] = trim( $match[ 2 ] );
			}
		}

		// Prüfe ob eine Variable auf eine andere Verweist
		return $sassVars;
	}
	return null;
}

/**
 * Diese Funktion gibt einen bestimmten SASS Variable zurück
 * @param string $varname Welche Varaible soll zurückgegeben werden
 * @param string $filename Den Pfad zur SASS-Datei
 * @return string|null Welchen Wert die Variable hat
 */
function getSassVar( $varname, $filename ) {
	$sassVars = getSassVars( $filename );
	if( !empty( $sassVars[ $varname ] ) ) {
		return $sassVars[ $varname ];
	}
	return null;
}

/**
 * Diese Funktion lösst einfache Referenzen auf.
 * @todo Diese Funktion sollte auch die Varaiblen ersetzten, die sich innerhalb
 * einer Funktion befinden.
 * zum Beispiel: "font-color( $theme-sidebar-bg, $white, $theme-text-color )"
 *
 * @param array $sassVars Die Varaiblen die von einer SASS-Datei geladen wurden
 * @return array Die aufgelösten Werte in einem Array
 */
function getSassResolvedVars( $sassVars ) {
	if( !empty( $sassVars ) && is_array( $sassVars ) ) {
		foreach( $sassVars as $sassKey => $sassVar ) {
			$sassVarNew = $sassVar;
			if( strpos( $sassVarNew, '$' ) === 0 ) {
				while( strpos( $sassVarNew, '$' ) === 0 ) {
					$sassVarNew = substr( $sassVarNew, 1 );
					if( !empty( $sassVars[ $sassVarNew ] ) ) {
						$sassVarNew = $sassVars[ $sassVarNew ];
					}
				}
				$sassVars[ $sassKey ] = $sassVarNew;
			}
		}
		return $sassVars;
	}
	return null;
}

/**
 * Ermittelt anhand von {@link wp_upload_dir()} den Pfad einer Datei.
 * @param string $url Die URL der Datei
 *
 * @return string
 */
function filePathFromURL( $url ) {
	$upload_dir = wp_upload_dir();

	$path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
	$path = DIRECTORY_SEPARATOR === '/'
		? str_replace( '\\', '/', $path )
		: str_replace( '/', '\\', $path );

	return $path;
}

?>
