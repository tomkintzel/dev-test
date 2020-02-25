<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

/**
 * Diese Klasse speichert eine HTTP-Session mit allen übergebenen Cookies.
 */
class HTTP_Session {
	/** @var string OPTION_NAME */
	const OPTION_NAME = 'msq_http_sessions';

	/** @var string $session_name */
	private $name;

	/** @var WP_Http_Cookie[] $cookies */
	public $cookies = [];

	/**
	 * Speichert den Namen des Session.
	 */
	public function __construct( $name ) {
		$this->name = $name;
		$this->load_session();
	}

	/**
	 * Diese Funktion lädt alle Werte von der letzten Session aus der Datenbank.
	 */
	public function load_session() {
		$sessions = get_option( self::OPTION_NAME );
		if( !empty( $sessions[ $this->name ] ) ) {
			$this->cookies = $sessions[ $this->name ][ 'cookies' ];
			$this->clean_cookies();
		}
	}

	/**
	 * Diese Funktion speichert die aktuelle Session in die Datenbank.
	 */
	public function save_session() {
		$this->clean_cookies();
		$sessions = get_option( self::OPTION_NAME, [] );
		$sessions[ $this->name ][ 'cookies' ] = $this->cookies;
		update_option( self::OPTION_NAME, $sessions );
	}

	/**
	 * Löscht die abgelaufenen Cookies.
	 */
	public function clean_cookies() {
		$time = time();
		foreach( $this->cookies as $cookie ) {
			if ( isset( $cookie->expires ) && $time > $cookie->expires ) {
				unset( $this->cookies[ $cookie->name ] );
			}
		}
	}

	/**
	 * Setzt alle Cookies zurück.
	 */
	public function reset_cookies() {
		$this->cookies = [];
	}

	/**
	 * Diese Funktion erstellt ein HTTP-Post-Anfrage, bei der
	 * die Cookies gesendet, geladen und gespeichert werden.
	 *
	 * @param string $url
	 * @param array $body
	 * @return boolean|array
	 */
	public function http_post( $url, $body ) {
		// Die Parameter bearbeiten
		$args = [
			'timeout' => 50,
			'redirection' => 0,
			'body' => $body
		];
		foreach( $this->cookies as $cookie ) {
			if( $cookie->test( $url ) ) {
				$args[ 'cookies' ][] = $cookie;
			}
		}

		// Sende die HTTP-Anfrage
		$response = wp_remote_post( $url, $args );

		// Bearbeite das Ergebniss
		if( is_wp_error( $response ) ) {
			trigger_error( $response->get_error_message() );
		} else {
			foreach( $response[ 'cookies' ] as $cookie ) {
				$this->cookies[ $cookie->name ] = $cookie;
			}
		}

		// Speichert die neuen Cookies
		$this->save_session();
		return $response;
	}

	/**
	 * Diese Funktion erstellt ein HTTP-Get-Anfrage, bei der
	 * die Cookies gesendet, geladen und gespeichert werden.
	 *
	 * @param string $url
	 * @return boolean|array
	 */
	public function http_get( $url ) {
		// Die Parameter bearbeiten
		$args = [
			'timeout' => 50,
			'redirection' => 0
		];
		foreach( $this->cookies as $cookie ) {
			if( $cookie->test( $url ) ) {
				$args[ 'cookies' ][] = $cookie;
			}
		}

		// Sende die HTTP-Anfrage
		$response = wp_remote_get( $url, $args );

		// Bearbeite das Ergebniss
		if( is_wp_error( $response ) ) {
			trigger_error( $response->get_error_message() );
		} else {
			foreach( $response[ 'cookies' ] as $cookie ) {
				$this->cookies[ $cookie->name ] = $cookie;
			}
		}

		// Speichert die neuen Cookies
		$this->save_session();
		return $response;
	}
}
