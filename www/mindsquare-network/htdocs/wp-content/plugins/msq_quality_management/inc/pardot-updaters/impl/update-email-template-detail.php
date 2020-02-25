<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;
use MSQ\Plugin\Quality_Management\Pardot_Models;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;

class Update_Email_Template_Details extends Update_Details {
	/** @var string EMAIL_TEMPLATE_MESSAGE_URL */
	const EMAIL_TEMPLATE_MESSAGE_URL = 'https://preview.pardot.com/emailTemplate/showHtmlMessage/id/%email_template_id%';

	/** @param int $email_template_id */
	public function execute( $email_template_id ) {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$email_template = $pardot_model_collection->get_email_template( $email_template_id );
		if( !empty( $email_template ) ) {
			$this->update_email_template_read( $email_template );
			$this->update_email_template_message( $email_template );
			$email_template->set_update_details_date( current_time( 'timestamp' ) );
		}
		$pardot_model_collection->save();
	}

	/** @param Pardot_Models\Email_Template &$email_template */
	private function update_email_template_read( &$email_template ) {
		// Lade die Read-Ansicht
		$url = $email_template->get_read_url();
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Email_Template_Details::update_email_template_read() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Name
		if( self::match_table_value( 'Name', $response[ 'body' ], $email_template_name ) ) {
			$email_template_name = html_entity_decode( trim( $email_template_name ) );
			$email_template->set_name( $email_template_name );
		}

		// Folder
		if( preg_match( '/folder#\/(?<folder_id>\d+)[^>]+>(?<folder_name>[^<]+)/is', self::match_table_value( 'Folder', $response[ 'body' ] ), $match ) ) {
			$folder_id = intval( $match[ 'folder_id' ] );
			$folder_name = html_entity_decode( trim( $match[ 'folder_name' ] ) );
			$folder = $pardot_model_collection->create_folder( $folder_id );
			$folder->set_name( $folder_name );
			$email_template->set_folder_id( $folder_id );
		}

		// Sender
		if( preg_match_all( '/<li>(?<email_template_sender>[^<]+)</is', self::match_table_value( 'Sender', $response[ 'body' ] ), $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$email_template_sender = html_entity_decode( trim( $match[ 'email_template_sender' ] ) );
				$email_template->add_sender( $email_template_sender );
			}
		}

		// Reply-To
		if( preg_match_all( '/<li>(?<email_template_reply>[^<]+)</is', self::match_table_value( 'Reply-To', $response[ 'body' ] ), $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$email_template_reply = html_entity_decode( trim( $match[ 'email_template_reply' ] ) );
				$email_template->add_reply( $email_template_reply );
			}
		}

		// Subject
		if( self::match_table_value( 'Subject', $response[ 'body' ], $email_template_subject ) ) {
			$email_template_subject = html_entity_decode( trim( $email_template_subject ) );
			$email_template->set_subject( $email_template_subject );
		}

		// Available For
		if( preg_match_all( '/<li>(?<email_template_available>[^<]+)</is', self::match_table_value( 'Available For', $response[ 'body' ] ), $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$email_template_available = html_entity_decode( trim( $match[ 'email_template_available' ] ) );
				$email_template->add_available( $email_template_available );
			}
		}

		// Tags
		if( preg_match_all( '/tag\/read\/id\/(?<tag_id>\d+)[^>]+>(?<tag_name>[^<]+)/is', self::match_table_value( 'Tags', $response[ 'body' ] ), $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$tag_id = intval( $match[ 'tag_id' ] );
				$tag_name = html_entity_decode( trim( $match[ 'tag_name' ] ) );
				$tag = $pardot_model_collection->create_tag( $tag_id );
				$tag->set_name( $tag_name );
				$email_template->add_tag_id( $tag_id );
			}
		}

		// Created At
		if( self::match_table_value( 'Created At', $response[ 'body' ], $form_create_date ) ) {
			$form_create_date = html_entity_decode( trim( $form_create_date ) );
			$email_template->set_create_date( strtotime( $form_create_date ) );
		}

		// Updated At
		if( self::match_table_value( 'Updated At', $response[ 'body' ], $form_edit_date ) ) {
			$form_edit_date = html_entity_decode( trim( $form_edit_date ) );
			$email_template->set_edit_date( strtotime( $form_edit_date ) );
		}

		// Created By
		if( preg_match( '/user\/read\/id\/(?<user_id>\d+)[^>]+>(?<user_name>[^<]+)/is', self::match_table_value( 'Created By', $response[ 'body' ] ), $match ) ) {
			$user_id = intval( $match[ 'user_id' ] );
			$user_name = html_entity_decode( trim( $match[ 'user_name' ] ) );
			$user = $pardot_model_collection->create_user( $user_id );
			$user->set_name( $user_name );
			$email_template->set_creator_id( $user_id );
		}

		// Updated By
		if( preg_match( '/user\/read\/id\/(?<user_id>\d+)[^>]+>(?<user_name>[^<]+)/is', self::match_table_value( 'Updated By', $response[ 'body' ] ), $match ) ) {
			$user_id = intval( $match[ 'user_id' ] );
			$user_name = html_entity_decode( trim( $match[ 'user_name' ] ) );
			$user = $pardot_model_collection->create_user( $user_id );
			$user->set_name( $user_name );
			$email_template->set_editor_id( $user_id );
		}
	}

	/** @param Pardot_Models\Email_Template &$email_template */
	public function update_email_template_message( &$email_template ) {
		// Lade die Read-Ansicht
		$session = new Pardot_Session();
		$url = str_replace( '%email_template_id%', $email_template->get_id(), self::EMAIL_TEMPLATE_MESSAGE_URL );
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Email_Template_Details::update_email_template_read() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Message
		if( preg_match( '/<body[^>]*>(?<message>.*)<\/body>/is', $response[ 'body' ], $match ) ) {
			$email_template_message = trim( html_entity_decode( $match[ 'message' ] ) );
			$email_template->set_message( $email_template_message );
		}
	}
}
