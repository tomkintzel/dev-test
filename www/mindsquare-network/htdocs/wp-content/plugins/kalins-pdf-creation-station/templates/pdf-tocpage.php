<?php
/**
 * Dieses Template wird als zweite Seite von einem PDF-Dokument verwendet.
 * Dabei erstellt dieses Template ein Inhaltsverzeichnis, welches aus den
 * Überschriften der einzelnen Posts besteht. Daher kann ein Inhaltsverzeichnis
 * nur bei mehreren Posts verwendet werden.
 *
 * @todo Das Inhaltsverzeichnis soll auch bei einzelnen Posts erstellt werden
 * können. Dabei sollen die Überschriften des Posts für das Inhaltsverzeichnis
 * verwendet werden.
 * @todo Auch bei mehreren Posts sollen die Überschriften verwendet werden.
 * Diese sollen allerdings als Unterpunkte aufgelistet werden.
 */

$this->addTOCPage();
$this->SetFont( $openSans, '', 12 );

$this->setHtmlVSpace( array(
	'table' => array(
		'h' => 8,
		'n' => 1
	)
));
$strHtml  = '
<style>
h3 {
	font-size: 24px;
	line-height: 30px;
	font-weight: bold;
	color: ' . $this->colors[ 'color-primary' ] . ';
}
</style>';
$strHtml .= '<h3>Inhalt</h3>';

$this->writeHTML( $strHtml, true, 0, true, 0 );
$this->Ln( 4 );

// Erstelle die Tabelle mit allen Einträge
$this->addHTMLTOC( 2, 'TOC', array(
	'<table border="0" cellpadding="0" cellspacing="0" style="font-weight:bold;font-size:14px;line-height:30px;"><tr><td width="145mm"><span>#TOC_DESCRIPTION#</span></td><td width="25mm"><span align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>',
	'<table border="0" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:30px;"><tr><td width="10mm">&nbsp;</td><td width="135mm"><span>#TOC_DESCRIPTION#</span></td><td width="25mm"><span align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>',
	'<table border="0" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:30px;"><tr><td width="20mm">&nbsp;</td><td width="125mm"><span>#TOC_DESCRIPTION#</span></td><td width="25mm"><span align="right">#TOC_PAGE_NUMBER#</span></td></tr></table>'
), true, 'B', array( 0, 0, 0 ) );

$this->endTOCPage();

?>
