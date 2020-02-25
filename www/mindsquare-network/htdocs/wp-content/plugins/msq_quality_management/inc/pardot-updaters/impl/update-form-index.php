<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Session;

class Update_Form_Index extends Async_Task {
	/** @var string FORM_TABLE_URL */
	const FORM_INDEX_URL = 'https://pi.pardot.com/form/table/ajax/1/page/%page%/sort/form.updated_at/order/desc/mode/manage/tagFilter/+/ajaxElement/form';

	/** @param boolean $all_pages */
	public function execute( $all_pages ) {
		$this->update_form_index( $all_pages );
	}

	/**
	 * Lädt die neuesten Daten von https://pi.pardot.com/form/forms.
	 * @param boolean $all_pages
	 */
	private function update_form_index( $all_pages ) {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		$session = $this->get_pardot_session();
		$page = 1;
		$last_page = 0;

		// Lade die Änderungen herunter
		do {
			$page_update_forms = 0;
			$page_founded_forms = 0;

			// Lade die Tabellen-Ansicht der Formulare
			$url = str_replace( '%page%', $page++, self::FORM_INDEX_URL );
			$response = $session->http_get( $url );
			if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
				trigger_error( 'Update_Form_Index::update_form_index() - Kein Content erhalten', E_USER_WARNING );
			}

			// Lade die maximale Anzahl an Seiten
			if( $last_page == 0 && preg_match( '/pager-last[^>]*>(?<last_page>[\d,]+)/is', $response[ 'body' ], $matches ) ) {
				$last_page = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $matches[ 'last_page' ] ) ) );
			}

			// Suche nach den Formularen
			$page_founded_forms = preg_match_all( '/form\/read\/id\/(?<form_id>\d+)[^>]*>(?<form_name>[^<]*)<(?<html>.*?)new-actions/is', $response[ 'body' ], $matches, PREG_SET_ORDER );
			if( $page_founded_forms > 0 ) {
				// Gehe durch alle gefundenen Formulare
				foreach( $matches as $match ) {
					$form_id = intval( $match[ 'form_id' ] );
					$form_name = html_entity_decode( $match[ 'form_name' ] );
					$form = $pardot_model_collection->create_form( $form_id );
					$form->set_name( $form_name );

					// Folder
					if( preg_match( '/folder#\/(?<folder_id>\d+).*?>(?<folder_name>.*?)</is', $match[ 'html' ], $sub_matches ) ) {
						$folder_id = intval( $sub_matches[ 'folder_id' ] );
						$folder_name = html_entity_decode( trim( $sub_matches[ 'folder_name' ] ) );
						$folder = $pardot_model_collection->create_folder( $folder_id );
						$folder->set_name( $folder_name );
						$form->set_folder_id( $folder_id );
					}

					// Total Views
					if( preg_match( '/<td *>.*?(?<form_total_views>[\d,]+)/is', $match[ 'html' ], $sub_matches ) ) {
						$form_total_views = intval( str_replace( ',', '', $sub_matches[ 'form_total_views' ] ) );
						$form->set_total_views( $form_total_views );
					}

					// Edit-Date
					if( preg_match( '/(?<form_edit_date>\w{3} \d+, \d+ \d+:\d+ \w{2})/is', $match[ 'html' ], $sub_matches ) ) {
						$form_edit_date = strtotime( $sub_matches[ 'form_edit_date' ] );
					}

					// Autoresponder
					if( preg_match( '/emailTemplate\/read\/id\/(?<email_template_id>\d+).*?>(?<email_template_name>.*?)</is', $match[ 'html' ], $sub_matches ) ) {
						$email_template_id = intval( $sub_matches[ 'email_template_id' ] );
						$email_template_name = html_entity_decode( $sub_matches[ 'email_template_name' ] );
						$email_template = $pardot_model_collection->create_email_template( $email_template_id );
						$email_template->set_name( $email_template_name );
						// Update Email-Template-Details
						if( min( $email_template->get_update_details_date(), $email_template->get_edit_date() ) < $form_edit_date ) {
							$pardot_model_updater->update_email_template_detail( $email_template_id );
						}
						$form->set_autoresponder_id( $email_template_id );
					}

					// Update Form-Details
					if( min( $form->get_update_details_date(), $form->get_edit_date() ) < $form_edit_date ) {
						$pardot_model_updater->update_form_detail( $form_id );
						$page_update_forms++;
					}

					// Update Form-Analyse
					if( min( $form->get_update_analyse_date(), $form->get_edit_date() ) < $form_edit_date ) {
						$pardot_model_updater->update_form_analyse( $form_id );
					}

					// Änderungen speichern
					$form->set_update_index_date( current_time( 'timestamp' ) );
					$form->set_edit_date( $form_edit_date );
				}
			}
		} while( $all_pages || $page_update_forms >= 50 && $page <= $last_page );

		// Speichere die Änderungen ab
		$pardot_model_updater->save();
		$pardot_model_collection->save();
	}

	/** @return Pardot_Session */
	private function get_pardot_session() {
		return new Pardot_Session();
	}
}
