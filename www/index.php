<?php
/**
 * Diese Datei listet alle Seiten auf.
 */
// Lade alle VHost-Dateien aus den Apache-Einstellungen
$vhost_path = '/etc/apache2/sites-enabled';
$vhost_files = scandir( $vhost_path );

// Speichert eine Liste gefundener Domains ab
$founded_domains = [];

// Durchsuche alle Dateien nach den Domains
foreach( $vhost_files as $vhost_file ) {
	$vhost_filename = sprintf( "%s/%s", $vhost_path, $vhost_file );
	if( is_file( $vhost_filename ) ) {
		// Lade den Inhalt der VHost-Datei
		$vhost_content = file_get_contents( $vhost_filename );

		// Suche nach neuen Domains
		if( preg_match_all( '/(?:ServerName|ServerAlias)(?<domains>[^\n]+)\n/i', $vhost_content, $vhost_matches, PREG_PATTERN_ORDER ) ) {
			foreach( $vhost_matches[ 'domains' ] as $domains ) {
				if( preg_match_all( '/(?<domain>\w{3,}[^ $]*)/', $domains, $domain_matches, PREG_PATTERN_ORDER ) ) {
					foreach( $domain_matches[ 'domain' ] as $domain ) {
						$founded_domains[ $domain ] = $vhost_file;
					}
				}
			}
		}
	}
}

// Gebe alle gefunden Domains aus
?>
<html>
	<head>
		<title>Registrierte Seiten</title>
	</head>
	<body>
		<h1>Registrierte Seiten</h1>
		<ul>
			<?php foreach( $founded_domains as $founded_domain => $vhost_file ): ?>
				<li>
					<?php printf( '<a href="//%s" target="_blank">%s</a>', $founded_domain, $founded_domain ); ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<h2></h2>
		<ul>
			<li>
				<a href="//localhost/phpinfo.php">PHP-Info</a>
			</li>
		</ul>
	</body>
</html>
