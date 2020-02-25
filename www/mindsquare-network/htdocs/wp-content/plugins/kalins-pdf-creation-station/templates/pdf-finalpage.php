<?php
/**
 * Dieses Template wird als letzte Seite von einem PDF-Dokuemnt verwendet.
 * Akutell kann diese Seite nur ausgepsielt werden, wenn in den Einstellungen
 * von diesem Plugin eine Eingabe für die 'finalPage' gemacht wurden.
 * Die Einstellungen gibt es dabei im Tool und auch in den Einstellungen für
 * die Einzelseiten.
 */
if( !empty( $this->oOptions->finalPage ) ) {
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
		$this->LinearGradient( 0, 0, 210, 297, hex2rgb( $this->colors[ 'color-primary-dark' ] ), hex2rgb( $this->colors[ 'color-primary-light' ] ), array( 0, 1, 1, 0 ) );
	}

	$finalPage = '';
	if( $this->isSingle ) {
		$finalPage = kalins_pdf_page_shortcode_replace( $this->oOptions->finalPage, $this->post );
	}
	else {
		$finalPage = kalins_pdf_global_shortcode_replace( $this->oOptions->finalPage );
	}
	$this->writeHTML( $finalPage, true, 0, true, 0 );

	$this->SetMargins( $margins[ 'left' ], $margins[ 'top' ], $margins[ 'right' ] );
	$this->SetAutoPageBreak( $autoPageBreak, $breakMargin );
}
?>
