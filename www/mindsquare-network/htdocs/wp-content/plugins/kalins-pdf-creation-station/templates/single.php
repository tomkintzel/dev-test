<?php
/**
 * Dieses Template wird für ein Post oder einem Page verwendet. Dabei werden
 * der Post-Content einfach als Text ausgegeben.
 *
 * @todo Aktuell werden die Informationen aus einem Post gezogen. Es kann
 * allerdings später bei einer Kategorie nicht verwendet werden.
 * @todo Die Buttons werden aktuell von einem externen Anbieter erzeugt.
 */
/** @var TCPDF $this */

$this->setPrintHeader( true );
$this->AddPage();
/** @todo @see wp-content\plugins\kalins-pdf-creation-station\templates\pdf-tocpage.php */
$this->Bookmark( $this->post->post_title, 0, 0 );
$this->setPrintFooter( true );

// Den gesamten Post-Content-Filter in eine Funktion bei includes zur Verfügung stellen
$content = $this->post->post_content;
// Ersetzte die YoutTube-Videos durch Links
if( $this->oOptions->convertYoutube ) {
	$content = preg_replace( '#\[embed\](.*)youtube.com/watch\?v=(.*)\[/embed]#', '<p><a href="http://www.youtube.com/watch?v=\\2">YouTube Video</a></p>', $content );
	$content = preg_replace( '#<iframe(.*)youtube.com/embed/(.*)[\'\"] (.*)</iframe>#', '<p><a href="http://www.youtube.com/watch?v=\\2">YouTube Video</a></p>', $content );
	$content = preg_replace( '#<object(.*)youtube.com/v/(.*)\"(.*)</object>#', '<p><a href="http://www.youtube.com/watch?v=\\2">YouTube Video</a></p>', $content );
}

// Ersetzte die Vimeo-Videos durch Links
if( $this->oOptions->convertVimeo ){
	$content = preg_replace( '#\[embed\](.*)vimeo.com(.*)\[/embed]#', '<p><a href="http://vimeo.com\\2">Vimeo Video</a></p>', $content );
	$content = preg_replace( '#<iframe(.*)player.vimeo.com/video/(.*)[\'\"] (.*)</iframe>#', '<p><a href="http://vimeo.com/\\2">Vimeo Video</a></p>', $content );
	$content = preg_replace( '#<object(.*)vimeo.com/moogaloop.swf\?clip_id=(.*)&amp;server(.*)</object>#', '<p><a href="http://vimeo.com/\\2">Vimeo Video</a></p>', $content );
}

// Ersetzte die Ted-Videos durch Links
if( $this->oOptions->convertTed ){
	$content = preg_replace( '#\[embed\](.*)ted.com(.*)\[/embed]#', '<p><a href="http://www.ted.com\\2">Ted Talk</a></p>', $content );
	$content = preg_replace( '#<iframe(.*)ted.com/(.*)[\'\"] (.*)</iframe>#', '<p><a href="http://www.ted.com/\\2.html">Ted Talk</a></p>', $content );
	$content = preg_replace( '#<object(.*)adKeys=talk=(.*);year=(.*)</object>#', '<p><a href="http://www.ted.com/talks/\\2.html">Ted Talk</a></p>', $content );
}

// Entferne den Caption-HTML-Tags
if( preg_match( '/\[caption +[^\]]*\]/', $content ) ) {
	$content = preg_replace( '/\[caption +[^\]]*\]/', '', $content );
	$content = preg_replace( '/\[\/caption\]/', '', $content );
}

// Entferne die Skript-HTML-Tags
if( preg_match( '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', $content ) ) {
	$content = preg_replace( '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $content );
}

// Entferne weitere spezifische Elemente
// Beim Post https://rz10.de/?p=12848 wird ein Video von fast.wistia.com hinzugefügt.
if( preg_match( '/class=[\"\'] *wistia_embed[\"\' ]/i', $content ) ) {
	$content = preg_replace( '/<(\w+)[^>]*class=[\"\'] *wistia_embed[\"\' ].*<\/\1>/i', '', $content );
}

// Entfernt mehrfache neue Zeilen
if( preg_match( '/(\r?\n){2,}/', $content ) ) {
	$content = preg_replace( '/(\r?\n){2,}/', '$1', $content );
}

// Sollen die Shortcodes ausgeführt werden
if( $this->oOptions->runShortcodes ) {
	$content = do_shortcode( $content );
}
else{
	$content = strip_shortcodes( $content );
}

// Für den 'the_content'-Filter, wenn diese ausgeführt werden soll
global $kalinsPDFRunning;
$kalinsPDFRunning = true;
if( $this->oOptions->runFilters ) {
	$content = apply_filters( 'the_content', $content );
}

// Prüfe ob Bilder von den Beiträgen hinzugefügt werden sollen
if( !$this->oOptions->includeImages ) {
	if( preg_match( '/<img[^>]+./', $content ) ) {
		$content = preg_replace( '/<img[^>]+./', '', $content );
	}
}

// Ersetzte die Buttons durch Bilder
$match_offset = 0;
while( preg_match( '/<(\w+)[^>]*class=[\'\"\w ]+btn_conversion[^>]+>(.+?)<\/\1>/i', $content, $match, PREG_OFFSET_CAPTURE, $match_offset ) ) {
	$match_offset = $match[ 2 ][ 1 ];
	/**
	 * @todo Aktuell wird hierfür dabuttonfactory verwendet. Allerdings
	 * sollte ein eigener Generator eingabut werden.
	 */
	$content = substr_replace( $content, '<br /><span style="text-align:center;"><img src="https://dabuttonfactory.com/button.gif?t=' . urlencode( strip_tags( $match[ 2 ][ 0 ] ) ) . '&f=Open+Sans-Bold&ts=22&tc=fff&hp=10&vp=10&c=3&bgt=gradient&bgc=' . substr( $this->colors[ 'color-secondary' ], 1 ) . '&ebgc=' . substr( $this->colors[ 'color-secondary-dark' ], 1 ) . '" alt="' . esc_attr( strip_tags( $match[ 2 ][ 0 ] ) ) . '"/></span>', $match[ 2 ][ 1 ], strlen( $match[ 2 ][ 0 ] ) );
}

/**
 * Hier werden einige HTML-Elemente entfernt oder bearbeitet, damit die
 * aktuellen Posts ohne große Bearbeitung als PDF verwendet werden kann.
 */
// Entferne leere p-Tags
$content = preg_replace( '/<p[^>]*> *<\/p>/i', '', $content );
// Entferne die Breite und die Höhe von den Bildern
while( preg_match( '/<img[^>]+(width|height)=[^>]+>/i', $content ) ) {
	$content = preg_replace( '/(<img[^>]+)(width|height)=[\"\'][^\"\'>]*[\"\']([^>]+>)/i', '$1$3', $content );
}
// Entferne die Breite von den Tabellen
if( preg_match( '/<table[^>]+width=[^>]+>/i', $content ) ) {
	$content = preg_replace( '/(<table[^>]+)width=[\"\'][^\"\'>]*[\"\']([^>]+>)/i', '$1$2', $content );
}
// Bilder zentrieren
//$content = preg_replace( '/(<a[^>]>)?(<img[^>]+>)(<\/a>)?/i', '<div style="text-align: center;">$1$2$3</div>', $content );

$this->setHtmlVSpace( array(
	'p' => array(
		0 => array(
			'h' => 1,
			'n' => 1
		),
		1 => array(
			'h' => 1,
			'n' => 4
		)
	),
	'h1' => array(
		0 => array(
			'h' => 8,
			'n' => 1
		),
		1 => array(
			'h' => 5,
			'n' => 1
		)
	),
	'h2' => array(
		0 => array(
			'h' => 8,
			'n' => 1
		),
		1 => array(
			'h' => 5,
			'n' => 1
		)
	),
	'h3' => array(
		0 => array(
			'h' => 7,
			'n' => 1
		),
		1 => array(
			'h' => 5,
			'n' => 1
		)
	),
	'h4' => array(
		0 => array(
			'h' => 7,
			'n' => 1
		),
		1 => array(
			'h' => 5,
			'n' => 1
		)
	),
	'li' => array(
		0 => array(
			'h' => 1,
			'n' => 1
		),
		1 => array(
			'h' => 1,
			'n' => 4
		)
	)
));

$headline_color = '';
$link_color = '';
$text_color = '';

if ( $this->currentBlog !== 37 ) {
	$headline_color = $this->colors['theme-headline-color'];
	$link_color = $this->colors['theme-link-color'];
	$text_color = '#000';
} else {
	$headline_color = $this->colors['dark-gray'];
	$link_color = $this->colors['orange'];
	$text_color = $this->colors['dark-gray'];
}

$strHtml  = '
<style>
* {
	font-size: 14px;
	line-height: 20px;
	font-weight: normal;
}
h1 {
	font-size: 30px;
	line-height: 32px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
h2 {
	font-size: 24px;
	line-height: 30px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
h3 {
	font-size: 18px;
	line-height: 30px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
h4 {
	font-size: 14px;
	line-height: 20px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
h5 {
	font-size: 14px;
	line-height: 20px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
h6 {
	font-size: 18px;
	line-height: 30px;
	font-weight: bold;
	color: ' . $headline_color . ';
}
p {
	text-align: justify;
	color: ' . $text_color . ';
}
li {
	color: ' . $text_color . ';
}
a {
	color: ' . $link_color . ';
}
.btn_conversion {
	text-align: center;
}
.btn_conversion img {
	margin-top: 20px;
	width: auto;
	height: 32px;
}
</style>';

/**
 * @todo Wenn auf dem Deckblatt bereits der Titel verwendet wurde, soll dann
 * über den Beitrag wirklich der Titel noch einmal ausgespielt werden?
 */
// Schreibe den Titel des Beitrags als H1 Element über den Beitrag
if( $this->post->post_type == 'page' ) {
	$beforePage = kalins_pdf_page_shortcode_replace( $this->oOptions->beforePage, $this->post );
	$afterPage = kalins_pdf_page_shortcode_replace( $this->oOptions->afterPage, $this->post );
	$content = $beforePage . $content . $afterPage;
}
else {
	$beforePage = kalins_pdf_page_shortcode_replace( $this->oOptions->beforePost, $this->post );
	$afterPage = kalins_pdf_page_shortcode_replace( $this->oOptions->afterPost, $this->post );
	$content = $beforePage . $content . $afterPage;
}
$strHtml .= wpautop( $content, true );

// Schrift-Einstellungen von der Seite weiter oben einbauen
$this->SetFont( $openSans, '', 12 );
$this->SetTextColor( 0 );
$this->writeHTML( $strHtml, true, 0, true, 0 );
