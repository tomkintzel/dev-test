<?php
/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/includes
 */
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ask_The_Author
 * @subpackage Ask_The_Author/includes
 * @author     Edwin Eckert
 */
class Ask_The_Author_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate( $network_wide ) {

		if ( is_plugin_active_for_network('acf-pardot/acf-pardot.php') ) {

			if ( is_multisite() && $network_wide) {

				global $wpdb;

				foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ) as $blog_id ) {

					switch_to_blog( $blog_id );
					
					self::update_descriptions_of_authors();

					restore_current_blog();

				}

			} else {

				self::update_descriptions_of_authors();

			}

		} else {

			$error_message = 'Dieses Plugin kann nur aktiviert werden, wenn das Plugin ACF-Pardot aktiviert ist!';

			die( $error_message );

		}

	}


	public static function update_descriptions_of_authors() {

		$args = array( 
			'fields' => array(
				'ID',
				'user_nicename',
				'display_name'
			)
		);

		$users = get_users( $args );

		foreach ( $users as $user ) {

			if ( empty( get_user_meta( $user->ID,  'description', true ) ) ) {

				update_user_meta( $user->ID, 'description', "Mein Name ist $user->display_name und ich bin begeisterter SAP Consultant bei mindsquare. Wie meine Kollegen habe ich mein Hobby zum Beruf gemacht." );

			}

		}

	}
}