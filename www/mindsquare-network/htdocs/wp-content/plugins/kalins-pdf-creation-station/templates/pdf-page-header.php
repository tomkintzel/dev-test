<?php
/**
 * Diese Datei erstellt eine Vorlage für den allgemeinen Header einer PDF-Seite.
 */
$margins = $this->getMargins();
$this->SetTextColor( 85 );
$this->SetMargins( $margins[ 'left' ], 15 );
$this->Multicell( 0, 15, $this->title, 0, 'C', 0, false, '', '', true, 0, false, true, 30, 'M', 'B', false );
$this->SetMargins( $margins[ 'left' ], $margins[ 'top' ], $margins[ 'right' ] );
?>
