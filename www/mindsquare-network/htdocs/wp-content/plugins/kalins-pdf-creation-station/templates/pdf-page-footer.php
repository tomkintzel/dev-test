<?php
/**
 * Dieses Template wird bei jeder Seite verwendet, die eine Kopf- und
 * Fuß-zeile haben.
 */
/** @var TCPDF $this */

// Die Seite soll beim erzeugen des Footers nicht umbrechen
$breakMargin   = $this->getBreakMargin();
$autoPageBreak = $this->getAutoPageBreak();
$margins       = $this->getMargins();
$this->SetAutoPageBreak( false, 0 );

// Weitere Einstellungen vom Footer
$this->SetTextColor( 255 );

$h    = 40;
$y    = $this->getPageHeight() - $h;
$w    = $this->getPageWidth();
$imgH = $h / 3;
$p    = ( $h - $imgH ) / 2;

if ( $this->currentBlog !== 37 ) {
	$this->LinearGradient( 0, $y, $w, $h, hex2rgb( $this->colors['color-primary-dark'] ),
		hex2rgb( $this->colors['color-primary-light'] ), array( 0, 1, 1, 0 ) );
} else {
	$color = hex2rgb( $this->colors['dark-gray'] );
	$this->Rect( 0, $y, $w, $h, 'F', [], $color );
}

// Fachbereichsleiter zeichnen
$contactImg = ! empty( $this->globalOptions->contactImg ) ? $this->globalOptions->contactImg : get_field( 'fb_img',
	'options' )['url'];
if ( ! empty( $contactImg ) ) {
	$this->Image( $contactImg, 15, 262, - 1, 30, '', '', '', false, 300, '', false, false, 0 );
}

// Inhalt erstellen
$this->SetFont( $openSans, '', 11 );
$this->Text( 55, 262, 'Ihr Ansprechpartner' );

// Inhalt erstellen
$this->SetXY( 55, 272 );
$this->SetMargins( 50, 272, 35 );
$contactName     = ! empty( $this->globalOptions->contactName ) ? $this->globalOptions->contactName : get_field( 'fb_name',
	'option' );
$contactPosition = ! empty( $this->globalOptions->contactPosition ) ? $this->globalOptions->contactPosition : get_field( 'fb_function',
	'option' );
$contactTel      = ! empty( $this->globalOptions->contactTel ) ? $this->globalOptions->contactTel : get_field( 'fb_phone',
	'option' );
$contactEmail    = ! empty( $this->globalOptions->contactEmail ) ? $this->globalOptions->contactEmail : get_field( 'fb_email',
	'option' );
$blogName        = ! empty( $this->globalOptions->blogName ) ? $this->globalOptions->blogName : get_option( 'blogname' );
$blogUrl         = ! empty( $this->globalOptions->blogUrl ) ? $this->globalOptions->blogUrl : get_option( 'home' );
$this->writeHTML( '<table style="font-weight: bold;"><tr><td width="150px">' . $contactName . '<br />' . $contactPosition . '<br />' . $blogName . '</td><td>Tel.: ' . $contactTel . '<br />' . $contactEmail . '<br />' . $blogUrl . '</td></tr>',
	true, 0, true, 0 );

// Logo zeichnen
$logo_url = null;

if ( $this->currentBlog === 37 ) {
	$logo_url = get_theme_mod( 'white_logo' );
}

if ( empty( $logo_url ) ) {
	$logo_url = get_theme_mod( 'header_logo' );

	if ( empty( $logo_url ) ) {
		$logo_url = get_theme_mod( 'fb_logo' );
	}
}

$this->Image( $logo_url, 165, 272, 35, - 1, '', '', '', false, 300, '', false, false, 0 );
$this->SetMargins( $margins['left'], 272, 15 );
$this->Multicell( 208, 15, 'Seite ' . $this->getAliasNumPage(), 0, 'R', true, 0, 0, 285, true, 0, false, true, 30,
	'M', 'B', false );

// Die Standardwerte von autoPageBreak und breakMargin zurücksetzten
$this->SetMargins( $margins['left'], $margins['top'], $margins['right'] );
$this->SetAutoPageBreak( $autoPageBreak, $breakMargin );
?>
