<?php
// Einstellungen von diesem PlugIn
define( 'MSQ_KALINS_TCPDF_PATH', dirname( __FILE__ ) . '/' );
define( 'MSQ_KALINS_TCPDF_TEMPLATES_PATH', MSQ_KALINS_TCPDF_PATH . 'templates/' );

global $openSans;


// Weitere Benutzereinstellungen laden
if ( ! empty( $_GET['singlepost'] ) ) {
	$get_singlepost = $_GET['singlepost'];
} elseif ( ! empty( $_POST['singlepost'] ) ) {
	$get_singlepost = $_POST['singlepost'];
}
if ( ! isset( $isSingle ) ) {
	$isSingle = isset( $get_singlepost );
}

// Ein Objekt zum Speichern der Nachrichten
$outputVar = new stdClass();

// Lade die benötigten Library-Dateien
try {
	require_once( MSQ_KALINS_TCPDF_PATH . '../../../wp-config.php' );
	require_once( MSQ_KALINS_TCPDF_PATH . 'tcpdf/tcpdf.php' );
//	require_once( MSQ_KALINS_TCPDF_PATH . 'tcpdf/fonts/')
	require_once( MSQ_KALINS_TCPDF_PATH . 'includes/class-msq-kalins-tcpdf.php' );
	require_once( MSQ_KALINS_TCPDF_PATH . 'includes/utility-msq-kalins-tcpdf.php' );
} catch ( Exception $error ) {
	$outputVar->status = 'Error(1): Eine oder mehrere Library-Dateien wurden nicht gefunden.';
	exit( json_encode( $outputVar ) );
}

$openSans = TCPDF_FONTS::addTTFfont( __DIR__ . '/opensans.ttf' );
$openSans = TCPDF_FONTS::addTTFfont( __DIR__ . '/opensans-bold.ttf' );

// Erstelle die Ordner Strukutr für das Cachen von PDF-Dokumenten
kalinsPDF_createPDFDir();

// Lade die Globalen Einstellungen
$globalOptions = kalins_pdf_get_options( KALINS_PDF_ADMIN_OPTIONS_NAME );


function sanitize_id( $id ) {
	$newId = $id;

	if ( ! ctype_digit( $id ) ) {
		$newId = preg_replace( "/[^0-9,.]/", "", $id );
	} else {
		$newId = $id;
	}

	$newId = intval( $newId );

	return $newId;
}

global $post;
if ( $isSingle ) {
	// Die Einstellungen für eine Einzelseite sind die Globalen Einstellungen
	$oOptions             = $globalOptions;
	$oOptions->includeTOC = false;

	if ( ! isset( $pageIDs ) ) {
		$pageIDs = $get_singlepost;
	}

	$singleID = sanitize_id( $pageIDs );

	// Nicht-Zahlen aus $pageID entfernen
	create_kalins_filedir( $singleID, $oOptions );
	$filePath = get_kalins_filepath( $singleID, $oOptions );
	$fileURL  = get_kalins_fileurl( $singleID, $oOptions );

	if ( file_exists( $filePath ) ) {
		if ( ! isset( $skipReturn ) ) {
			echo '<body><script type="text/javascript">top.location = "' . $fileURL . '"</script></body>';
		}
		exit();
	} else {
		$outputVar->fileName = get_kalins_filename( $singleID );
		$outputVar->date     = date( 'Y-m-d H:i:s', time() );
	}
} else {
	try {
		$pdfDir = KALINS_PDF_DIR;

		$request_body = json_decode( trim( file_get_contents( 'php://input' ) ) );
		$oOptions     = $request_body->oOptions;

		// Speichere die Einstellungen
		$templates               = kalins_pdf_get_options( KALINS_PDF_TOOL_TEMPLATE_OPTIONS_NAME );
		$templates->sCurTemplate = $oOptions->templateName;
		update_option( KALINS_PDF_TOOL_TEMPLATE_OPTIONS_NAME, $templates );

		// $pageIDs are sent on the request instead of the oOptions object because they are not saved to the database
		$pageIDs = $request_body->pageIDs;

		if ( ! empty( $oOptions->filename ) ) {
			$filename = kalins_pdf_global_shortcode_replace( $oOptions->filename );
			$filename = str_replace( '&#039;', '', $filename );
			$filename = str_replace( '\'', '', $filename );
			$filename = str_replace( '/', '', $filename );
			$filename = stripslashes( $filename );
		} else {
			//if user did not enter a filename, we use the current timestamp as a filename (mostly just to streamline testing)
			$filename = time();
		}
	} catch ( Exception $error ) {
		$outputVar->status = 'Error(2): Die übergebenen Einstellungen sind fehlerhaft. Prüfe die Eingabe, oder setzte die Einstellungen zurück.';
		exit( json_encode( $outputVar ) );
	}

	$outputVar->aFiles = array();
	if ( file_exists( $pdfDir . $filename . '.pdf' ) ) {
		// if a file already exists, error and quit
		$outputVar->status = $filename . '.pdf existiert bereits.';
		exit( json_encode( $outputVar ) );
	}

	$newFileObj           = new stdClass();
	$newFileObj->fileName = $filename . '.pdf';
	$newFileObj->date     = date( 'Y-m-d H:i:s', time() );
	array_push( $outputVar->aFiles, $newFileObj );
}

/**
 * Alle Einträge der übergebene PostID-Liste und die TermID-Liste werden aus
 * der Datenbank in eine Liste gespeichert.
 * Wenn eine TermID-Liste aber nicht eine PostID-Liste übergeben wurde, dann
 * werden aus diesen Terms die gefundenen Posts hinzugefügt.
 * Wie die Reihenfolge der übergebenen IDs ist, so werden die Seiten später
 * angelegt.
 */
try {
	/**
	 * @todo Diese Funktion wird noch nicht gebraucht.
	 *
	 * if( !empty( $termIdList ) ) {
	 * // Entferne die doppelten Einträge
	 * $termIdList = array_unique( $termIdList );
	 *
	 * // Lade die Liste der Terms
	 * $termList = get_terms( array(
	 * 'include' => $termIdList
	 * // 'orderby' => 'include',
	 * // 'number' => -1
	 * ))
	 *
	 * // Wenn die Liste der Posts leer ist, dann die Posts von den Terms laden
	 * if( empty( $postIdList ) ) {
	 * /** @todo Hier eine Liste von Posts * /
	 * }
	 * }
	 */
	/**
	 * @todo Diese Funktion wird noch nicht gebraucht.
	 *
	 * if( !empty( $postIdList ) ) {
	 * // Entferne die doppelten Einträge
	 * $postIdList = array_unique( $postIdList );
	 *
	 * // Lade die Liste der Querys
	 * $postList = get_posts( array(
	 * 'include' => $postIdList,
	 * 'orderby' => 'post__in',
	 * 'number' => -1
	 * ));
	 * }
	 */
	/**
	 * Lädt eine Liste von Posts.
	 * @todo Diese Funktion soll durch die obere ersetzt werden.
	 */
	$postList = array();
	foreach ( explode( ',', $pageIDs ) as $pageID ) {
		array_push( $postList, get_post( sanitize_id( $pageID ) ) );
	}
} catch ( Exception $error ) {
	$outputVar->status = "Error(3): Die Post Liste konnten nicht geladen werden.";
	exit( json_encode( $outputVar ) );
}

/**
 * Sammel weitere Informationen von den übergebenen Listen. Zum Beispiel soll
 * geprüft werden, welche Autoren bei den Posts mitgewirkt haben.
 */
try {
	/**
	 * @todo Diese Funktion wird nicht gebraucht.
	 *
	 * if( !empty( $termList ) ) {
	 * if( count( $termList ) == 1 ) {
	 * $term = reset( $termList );
	 * }
	 * }
	 */
	if ( ! empty( $postList ) ) {
		/**
		 * @todo Diese Funktion wird noch nicht gebraucht.
		 *
		 * // Erstelle eine Liste von Authoren IDs
		 * $authorIdList = array();
		 *
		 * // Gehe durch alle Posts und sammel die Authoren IDs
		 * foreach( $postList as $post ) {
		 * $authorIdList[] = $post->post_author;
		 * }
		 * // Entferne die doppelten Einträge
		 * $authorIdList = array_unique( $authorIdList );
		 *
		 * // Gehe durch die List
		 * $authorList = wp_list_authors( array(
		 * 'include' => $authorIdList
		 * ));
		 */

		if ( count( $postList ) == 1 ) {
			$post = reset( $postList );
		}
		/**
		 * @todo Diese Funktion wird nicht gebraucht.
		 *
		 * if( count( $authorList ) == 1 ) {
		 * $author = reset( $authorList );
		 * }
		 */
	}
} catch ( Exception $error ) {
	$outputVar->status = "Error(4): Es konnten leider keine weiteren Informationen geladen werden.";
	exit( json_encode( $outputVar ) );
}

/**
 * Hier wird ein neues PDF-Dokument mit TCPDF erstellt. Hierbei wrid allerdings
 * ein eigene Klasse verwendet, damit einige Funktionen ersetzt, da eigene
 * Funktionen hinzugefügt wurden.
 */
try {
	// Neues PDF-Dokument erstellen
	$pdf = new MSQ_Kalins_TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true ); // 'P', 'mm', 'A4'

	// Speichere die Einstellungen vom Plugin ab
	$pdf->oOptions      = $oOptions;
	$pdf->globalOptions = $globalOptions;
	$pdf->isSingle      = $isSingle;

	// Speichere die Farben mit Verergbung
	$stylesheetColors = [];
	$templateColors   = [];
	$currentBlog      = get_current_blog_id();

	if( $currentBlog == 37 ) {
		$stylesheetColors = getSassVars( get_stylesheet_directory() . '/assets/sass/base/_variables.scss' ) ?: [];
	} else if( $currentBlog == 28 ) {
		$stylesheetColors[ 'color-primary-dark' ] = '#1b3257';
		$stylesheetColors[ 'color-primary' ] = '#1b3257';
		$stylesheetColors[ 'color-primary-light' ] = '#1665a6';
		$stylesheetColors[ 'color-secondary' ] = '#4fb74c';
		$stylesheetColors[ 'color-secondary-dark' ] = '#4fb74c';
		$templateColors   = getSassVars( get_template_directory() . '/assets/sass/base/_colors.scss' ) ?: [];
	} else {
		$stylesheetColors = getSassVars( get_stylesheet_directory() . '/assets/sass/base/_colors.scss' ) ?: [];
		$templateColors   = getSassVars( get_template_directory() . '/assets/sass/base/_colors.scss' ) ?: [];
	}

	$pdf->colors      = getSassResolvedVars( array_merge( $templateColors, $stylesheetColors ) );
	$pdf->currentBlog = $currentBlog;

	// PDF-Dokuemnt Informationen einstellen
	if ( ! empty( $post ) ) {
		// Speichere den gefunden Post-Type
		$pdf->post = $post;

		// Speichere den Author vom PDF Dokument
		$author = array_map( function ( $meta_value ) {
			return $meta_value[0];
		}, get_user_meta( $post->post_author ) );
		$pdf->SetAuthor( $author['first_name'] . ' ' . $author['last_name'] );
	} else {
		$pdf->SetAuthor( 'mindsquare' );
	}

	// Stelle den Titel vom PDF-Dokument ein
	if ( ! empty( $oOptions->headerTitle ) ) {
		// Nehme den Titel von den Einstellungen
		if ( ! empty( $post ) ) {
			// Für
			$title = htmlspecialchars_decode( kalins_pdf_page_shortcode_replace( $oOptions->headerTitle, $post ) );
		} else {
			$title = htmlspecialchars_decode( kalins_pdf_global_shortcode_replace( $oOptions->headerTitle ) );
		}
		$title = str_replace( '&#039;', '\'', $title );
	} /**
	 * @todo Diese Funktion wird noch nicht gebraucht.
	 *
	 * else if( !empty( $term ) ) {
	 * // Nehme den Term-Namen
	 * $title = $term->name;
	 * }
	 */
	elseif ( ! empty( $post ) ) {
		// Nehme den Post-Titel
		$title = $post->post_title;
	} /**
	 * @todo Diese Funktion wird noch nicht gebraucht.
	 *
	 * else if( !empty( $termList ) ) {
	 * // Nehme den ersten Term-Namen
	 * $title = reset( $termList )->name;
	 * }
	 */
	elseif ( ! empty( $postList ) ) {
		// Nehme den Ersten Post-Titel
		$title = reset( $postList )->post_title;
	} else {
		// Es wurde überhaupt kein Title gefunden
		$title = 'mindsquare E-Book';
	}
	// Speichere den Titel
	if ( ! empty( $title ) ) {
		$pdf->SetTitle( $title );
	} else {
		$title = '';
	}

	// Stelle den Subtitle vom PDF-Dokuemnt ein
	if ( ! empty( $oOptions->headerSub ) ) {
		// Nehme den Titel von den Einstellungen
		if ( ! empty( $post ) ) {
			$subtitle = htmlspecialchars_decode( kalins_pdf_page_shortcode_replace( $oOptions->headerSub, $post ) );
		} else {
			$subtitle = htmlspecialchars_decode( kalins_pdf_global_shortcode_replace( $oOptions->headerSub ) );
		}
		$subtitle = str_replace( '&#039;', '\'', $subtitle );
	}
	/** @todo Hier weitere Felder hinzufügen, die den Subtitle einstellen können */
	if ( ! empty( $subtitle ) ) {
		$pdf->SetSubject( $subtitle );
	} else {
		$subtitle = '';
	}

	/** @todo Diesen Befehl weiter bearbeiten. Hier kann noch ein Logo eingebaut werde */
	// Die Headline vom PDF-Fenster bearbeiten
	$pdf->SetHeaderData( null, null, $title, $subtitle );

	/**
	 * @todo Diese Funktion wird noch nicht gebraucht
	 *
	 * $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
	 */

	// Standardeinstellungen
	$pdf->SetCreator( PDF_CREATOR ); // 'TCPDF'
	$pdf->setHeaderFont( array( $openSans, '', 12 ) );
	$pdf->setFooterFont( array( $openSans, '', 12 ) );
	$pdf->SetMargins( 20, 30, 20 ); // 15, 27, 15
	$pdf->SetHeaderMargin( PDF_MARGIN_HEADER ); // 5
	$pdf->SetFooterMargin( PDF_MARGIN_FOOTER ); // 10
	$pdf->SetAutoPageBreak( true, 50 ); // 25
	$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
} catch ( Exception $error ) {
	$outputVar->status = "Error(5): Ein Problem bei den Einstellungen gefunden. Prüfe die Eingaben für den Titel.";
	exit( json_encode( $outputVar ) );
}

/**
 * Hier werden die Seiten erstellt.
 *
 * @todo Der Aufbau ist aktuell nur für eine Single-Seite
 */
try {
	// Erstelle die Startseite
	$pdf->LoadTemplatePart( 'pdf-titlepage' );

	if ( ! empty( $post ) ) {
		// Nur ein Eintrag
		$pdf->post = $post;
		$pdf->LoadTemplate( 'single', array( $post->post_type, $post->post_name ) );
	} elseif ( ! empty( $postList ) ) {
		// Mehrere Einträge
		foreach ( $postList as $post ) {
			$pdf->post = $post;

			// Füge ein Eintrag in das Inhaltsverzeichnis hinzu
			$pdf->LoadTemplate( 'single', array( $post->post_type, $post->post_name ) );
		}

		// Wenn ein Inhaltsverzeichnis hinzugefügt werden soll
		if ( $oOptions->includeTOC ) {
			$pdf->LoadTemplate( 'pdf-tocpage' );
		}
	}

	// Erstelle die letzte Seite
	$pdf->LoadTemplatePart( 'pdf-finalpage' );
} catch ( Exception $error ) {
	$outputVar->status = "Error(6): Es ist ein Problem beim erstellen dre Seiten aufgetretten.";
	exit( json_encode( $outputVar ) );
}

/**
 * Hier wird die Datei gespeichert.
 */
try {
	if( !empty( $filePath ) ):
		$pdf->Output( $filePath, 'F' );
	else:
		$pdf->Output( $filename, 'F' );
	endif;
	$outputVar->status = "success";
} catch ( Exception $error ) {
	$outputVar->status = "Error(7): Die PDF-Datei konnte leider nicht erstellt werden.";
	exit( json_encode( $outputVar ) );
}

/**
 * Hier werden die passenden Ausgaben erzeugt. Der Benutezr wird weiter zum
 * PDF-Dokument weitergeleitet.
 */
if ( ! isset( $skipReturn ) ) {
	if ( $isSingle ) {
		echo '<body><script type="text/javascript">top.location = "' . $fileURL . '"</script></body>';
		exit();
	} else {
		exit( json_encode( $outputVar ) );
	}
}
?>
