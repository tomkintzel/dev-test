<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;

class Update_Email_Template_Index extends Async_Task {
	/** @var string FORM_TABLE_URL */
	const EMAIL_TEMPLATE_INDEX_URL = 'https://pi.pardot.com/emailTemplate/table/ajax/1/page/%page%/sort/email_template.updated_at/order/desc/dateRange/6/tagFilter/+/ajaxElement/emailTemplate/';

	/** @var string EMAIL_TEMPLATE_DRAFT_INDEX_URL */
	//const EMAIL_TEMPLATE_DRAFT_INDEX_URL = 'https://pi.pardot.com/email/template/draft/table/ajax/1/page/%page%/sort/email_template_draft.name/order/desc/dateRange/6/tagFilter/+/ajaxElement/emailTemplateDraft/';

	/** @param boolean $all_pages */
	public function execute( $all_pages ) {
		$this->update_email_template_index( self::EMAIL_TEMPLATE_INDEX_URL, $all_pages );
		//$this->update_email_template_index( self::EMAIL_TEMPLATE_DRAFT_INDEX_URL, $all_pages );
	}

	/**
	 * @param string $url
	 * @param boolean $all_pages
	 */
	private function update_email_template_index( $raw_url, $all_pages ) {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		$session = new Pardot_Session();
		$page = 1;
		$last_page = 0;

		// Lade die Änderungen herunter
		do {
			$page_update_email_templates = 0;
			$page_founded_email_templates = 0;

			// Lade die Tabellen-Ansicht der Formulare
			$url = str_replace( '%page%', $page++, $raw_url );
			$response = $session->http_get( $url );
			if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
				trigger_error( 'Update_Email_Template_Index::update_email_template_index() - Kein Content erhalten', E_USER_WARNING );
			}

			// Lade die maximale Anzahl an Seiten
			if( $last_page == 0 && preg_match( '/pager-last[^>]*>(?<last_page>[\d,]+)/is', $response[ 'body' ], $matches ) ) {
				$last_page = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $matches[ 'last_page' ] ) ) );
			}

			// Suche nach den Formularen
			$page_founded_email_templates = preg_match_all( '/emailTemplate\/read\/id\/(?<email_template_id>\d+).*?>(?<email_template_name>.*?)<(?<html>.*?)(?<email_template_edit_date>\w{3} \d+, \d+ \d+:\d+ \w{2})/is', $response[ 'body' ], $matches, PREG_SET_ORDER );
			if( $page_founded_email_templates > 0 ) {
				// Gehe durch alle gefundenen Formulare
				foreach( $matches as $match ) {
					$email_template_id = intval( $match[ 'email_template_id' ] );
					$email_template_name = html_entity_decode( $match[ 'email_template_name' ] );
					$email_templat_edit_date = strtotime( $match[ 'email_template_edit_date' ] );
					$email_template = $pardot_model_collection->create_email_template( $email_template_id );
					$email_template->set_name( $email_template_name );

					// Prüfe ob ein Update durchgeführt werden muss
					if( $email_template->get_edit_date() < $email_templat_edit_date ) {
						$pardot_model_updater->update_email_template_detail( $email_template_id );
						$page_update_email_templates++;
					}
					$email_template->set_update_index_date( current_time( 'timestamp' ) );
					$email_template->set_edit_date( $email_templat_edit_date );
				}
			}
		} while( $all_pages || $page_update_email_templates >= 50 && $page <= $last_page );

		// Speichere die Änderungen ab
		$pardot_model_updater->save();
		$pardot_model_collection->save();
	}
}
