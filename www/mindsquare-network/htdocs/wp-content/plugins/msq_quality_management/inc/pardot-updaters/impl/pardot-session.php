<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\HTTP_Session;

/**
 * Diese Klasse erstellt eine Session bei der https://go.pardot.com/ Seite.
 */
class Pardot_Session extends HTTP_Session {
	/** @var string SESSION_NAME */
	const SESSION_NAME = 'pardot';

	/** @var string LOGINPAGE_URL */
	const LOGINPAGE_URL = 'https://pi.pardot.com/';

	/** @var string LOGINFORM_URL */
	const LOGINFORM_URL = 'https://pi.pardot.com/user/login';

	/**
	 * Beim erstellen dieser Session wird ein Loggin durchgeführt.
	 */
	public function __construct() {
		parent::__construct( self::SESSION_NAME );
		if( !$this->is_logged_in() ) {
			$this->login();
		}
	}

	/**
	 * Diese Funktion loggt diese Session ein.
	 */
	public function login() {
		// Suche nach den Zugangsdaten
		// @todo Hier müssen die Daten ersetzt werden
		$pardot_settings = null; // Pardot_Settings::get_settings();
		if( !empty( $pardot_settings ) ) {
			$email = $pardot_settings[ 'email' ];
			$password = $pardot_settings[ 'password' ];
		} else {
			$email = 'Genschel@mindsquare.de';
			$password = 'OxfordUniGen2219!'; 
		}

		// Suche nach dem '_csrf_token'
		$response = $this->http_get( self::LOGINPAGE_URL );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Pardot_Session::login() - ' . $response->get_error_message(), E_USER_WARNING );
			return false;
		}
		if( !preg_match( '/name="_csrf_token"[^>]*?value="([^"]*)"/i', $response[ 'body' ], $matches ) ) {
			trigger_error( 'Pardot_Session::login() - Kein Token gefunden', E_USER_WARNING );
			return false;
		}
		$csrf_token = $matches[ 1 ];

		// Loginversuch
		$response = $this->http_post( self::LOGINFORM_URL, [
			'_csrf_token' => $csrf_token,
			'password' => $password,
			'email_address' => $email,
			'commit' => 'Log In'
		]);
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Pardot_Session::login() - Login fehlgeschlagen', E_USER_WARNING );
			return false;
		}

		// Prüfe ob der Loginversuch erfolgreich war
		return $response[ 'headers' ]->offsetGet( 'location' ) == '/home/index';
	}

	/**
	 * Prüft ob diese Session bei https://go.pardot.com/ eingeloggt ist.
	 * @return boolean
	 */
	public function is_logged_in() {
		$this->clean_cookies();
		return !empty( $this->cookies[ 'oauthLoginSecureKey' ] );
	}
}
