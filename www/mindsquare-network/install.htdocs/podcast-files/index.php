<?php
/**
 * Diese Datei lädt fehlende Dateien herunter und gibt diese einem Request zur Verfügung.
 */
// Einstellungen für die Ausgabe
error_reporting( 0 );
ini_set( 'display_errors', '0' );

// Informationen über den Request sammeln
$redirect_uri = $_SERVER[ 'REQUEST_URI' ];
$server_name = $_SERVER[ 'SERVER_NAME' ];
$filename = dirname( __FILE__, 2 ) . preg_replace( '/\/$/', '', $redirect_uri );

// Prüfe ob die Datei nicht existiert
if( !file_exists( $filename ) ) {
	// Erstelle eine Verbindung zum Original-Server und lade die fehlende Datei herunter
	$ch = curl_init( "https://$server_name$redirect_uri" );

	// Einstellungen für die Verbindung
	curl_setopt( $ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
	curl_setopt( $ch, CURLOPT_RESOLVE, ["$server_name:443:185.88.215.50"] );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

	// Führe die Anfrage aus
	$data = curl_exec( $ch );

	// Speichere die Datei ab
	if( !empty( $data ) ) {
		$dirname = dirname( $filename );
		mkdir( $dirname , 0777, true );
		$new_file = fopen( $filename, 'x' );
		fwrite( $new_file, $data );
		fclose( $new_file );
	}

	// Schließe die Verbindung
	curl_close( $ch );
} else {
	// Lade den Inhalt der der Datei
	$data = file_get_contents( $filename );
}

// Wenn die Datei nicht gefunden wurde
if( empty( $data ) ) {
	http_response_code( 404 );
	exit();
}

// Erstelle die benötigten Header
$content_type = mime_content_type( $filename );
$file_size = filesize( $filename );
header( "Content-Type: $content_type" );
header( "Content-Length: $file_size" );

// Ausgabe der Datei
exit( $data );
