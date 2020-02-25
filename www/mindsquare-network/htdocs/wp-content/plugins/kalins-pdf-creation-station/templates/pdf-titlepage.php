<?php
/**
 * Dieses Template wird zu beginn eines PDF-Dokuments ausgepsielt und erzeugt
 * ein Deckblatt.
 *
 * @todo Aktuell werden die Informationen aus einem Post gezogen.
 * Es kann allerdings später bei einer Kategorie nicht verwendet werden.
 */
/** @var TCPDF $this */
// Erstelle die Title-Seite
$this->setPrintHeader( false );
$this->AddPage();
$this->setPrintFooter( false );
$this->SetFont( $openSans, '', 22 );
$this->SetTextColor( 255 );

// Die Seite soll beim erzeugen nicht umbrechen
$breakMargin = $this->getBreakMargin();
$autoPageBreak = $this->getAutoPageBreak();
$margins = $this->getMargins();
$this->SetAutoPageBreak( false, 0 );

// Zeichne das Hintergrundbild
if( !empty( $this->globalOptions->titlepageBGImg ) ) {
	$this->Image( $this->globalOptions->titlepageBGImg, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0 );
}
else {
	if ( $this->currentBlog !== 37 ) {
		$this->LinearGradient( 0, 0, 210, 297, hex2rgb( $this->colors['color-primary-dark'] ),
			hex2rgb( $this->colors['color-primary-light'] ), array( 0, 1, 1, 0 ) );
	} else {
		$this->LinearGradient( 0, 0, 210, 297,
			hex2rgb( $this->colors['orange'] ),
			hex2rgb( $this->colors['yellow'] ),
			[0, 0, 1, 0]
		);
	}
}

if( !empty( $this->oOptions->titlePage ) ) {
	$this->SetY( $margins[ 'left' ] );

	$titlePage = '';
	if( $this->isSingle ) {
		$titlePage = kalins_pdf_page_shortcode_replace( $this->oOptions->titlePage, $this->post );
	}
	else {
		$titlePage = kalins_pdf_global_shortcode_replace( $this->oOptions->titlePage );
	}
	$this->writeHTML( $titlePage, true, 0, true, 0 );
}
else {
	if ( $this->currentBlog !== 37 ) {
		// Das Logo Bild Zur Seite hinzufügen
		$logo_url = get_theme_mod( 'header_logo' );
		if ( empty( $logo_url ) ) {
			$logo_url = get_theme_mod( 'fb_logo' );
		}
	} else {
		$logo_url = get_theme_mod( 'white_logo' );

		if ( empty( $logo_url ) ) {
			$logo_url = get_theme_mod( 'header_logo' );
		}
	}

	if ( empty( $logo_url ) ) {
		trigger_error('Konnte kein Logo für generierte PDFs finden');
	} else {
		$logo_path = filePathFromURL( $logo_url );
		$logo_alt  = get_theme_mod( 'blogname' );
		$this->Image( $logo_path, $margins['left'], $margins['left'], 55, 0, 'PNG', '', 'N',
			false, 300, '', false, false, 0, false, false,
			false, $logo_alt );

		$newX = $this->getImageRBY();
		$this->SetY( $newX + 10 );
	}

	// Den Titel zur Seite hinzufügen
	$this->ln( 12 );
	$this->Text( $this->GetX(), $this->GetY(), $this->title );
	//$this->writeHTML( '<h1>' . $this->title . '</h1>', true, 0, true, 0 );

	/**
	 * Wenn ein Post gefunden wurde, dann soll von diesem ein Bild verwendet
	 * werden.
	 */
	if( !empty( $this->post ) ) {
		$post_thumnail_id = get_post_thumbnail_id( $this->post->ID );
		$post_thumnail_arr = wp_get_attachment_image_src( $post_thumnail_id, 'full' );
		// Wenn ein Bild gefunden wurde
		if( !empty( $post_thumnail_arr[ 0 ] ) ) {
			$this->ln( 16 );
			$this->Image( $post_thumnail_arr[ 0 ], $margins[ 'left' ], '', 210 - $margins[ 'left' ] - $margins[ 'right' ], 0, '', '', '', true, 300, 'C', false, false, array(
				'LTRB' => array(
					'width' => 1,
					'color' => array( 255, 255, 255 )
				)
			));
		}
	}
}

// Die Standardwerte zurücksetzten
$this->SetMargins( $margins[ 'left' ], $margins[ 'top' ], $margins[ 'right' ] );
$this->SetAutoPageBreak( $autoPageBreak, $breakMargin );
?>
