<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;
use MSQ\Plugin\Quality_Management\Pardot_Models;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;

class Update_Form_Details extends Update_Details {
	/** @var string REPORT_URL */
	const REPORT_URL = 'https://pi.pardot.com/form/readReport/id/%id%';

	/** @var string WIZARD_URL */
	const WIZARD_URL = 'https://pi.pardot.com/form/wizardStep%page%/object_id/%id%/wizardType/9/isRead/true';

	/** @param int $form_id */
	public function execute( $form_id ) {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		$form = $pardot_model_collection->get_form( $form_id );
		if( !empty( $form ) ) {
			$this->update_form_read( $form );
			$this->update_form_wizzard_step_2( $form );
			$this->update_form_wizzard_step_3( $form );
			$this->update_form_wizzard_step_5( $form );
			$this->update_form_read_report( $form );
			$form->set_update_details_date( current_time( 'timestamp' ) );

			$email_template = $form->get_autoresponder();
			if( !empty( $email_template ) ) {
				$pardot_model_updater->update_email_template_detail( $email_template->get_id() );
			}
		}
		$pardot_model_updater->save();
		$pardot_model_collection->save();
	}

	/** @param Pardot_Models\Form &$form */
	private function update_form_read( &$form ) {
		// Lade die Read-Ansicht der Formulare
		$url = $form->get_read_url();
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_form_read() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Name
		if( self::match_table_value( 'Name', $response[ 'body' ], $form_name ) ) {
			$form_name = html_entity_decode( trim( $form_name ) );
			$form->set_name( $form_name );
		}

		// Folder
		if( preg_match( '/folder#\/(?<folder_id>\d+)[^>]+>(?<folder_name>[^<]+)/is', self::match_table_value( 'Folder', $response[ 'body' ] ), $match ) ) {
			$folder_id = intval( $match[ 'folder_id' ] );
			$folder_name = html_entity_decode( trim( $match[ 'folder_name' ] ) );
			$folder = $pardot_model_collection->create_folder( $folder_id );
			$folder->set_name( $folder_name );
			$form->set_folder_id( $folder_id );
		}

		// Campaign
		if( preg_match( '/campaign\/read\/id\/(?<campaign_id>\d+)[^>]+>(?<campaign_name>[^<]+)/is', self::match_table_value( 'Campaign', $response[ 'body' ] ), $match ) ) {
			$campaign_id = intval( $match[ 'campaign_id' ] );
			$campaign_name = html_entity_decode( trim( $match[ 'campaign_name' ] ) );
			$campaign = $pardot_model_collection->create_campaign( $campaign_id );
			$campaign->set_name( $campaign_name );
			$form->set_campaign_id( $campaign_id );
		}

		// Tracker Domain
		if( preg_match( '/trackerDomain\/read\/id\/(?<tracking_domain_id>\d+)[^>]+>(?<tracking_domain_name>[^<]+)/is', self::match_table_value( 'Tracker Domain', $response[ 'body' ] ), $match ) ) {
			$tracking_domain_id = intval( $match[ 'tracking_domain_id' ] );
			$tracking_domain_name = html_entity_decode( trim( $match[ 'tracking_domain_name' ] ) );
			$tracking_domain = $pardot_model_collection->create_tracking_domain( $tracking_domain_id );
			$tracking_domain->set_name( $tracking_domain_name );
			$form->set_tracking_domain_id( $tracking_domain_id );
		}

		// Link
		if( preg_match( '/<a[^>]+>(?<form_link>[^<]+)/is', self::match_table_value( 'Link', $response[ 'body' ] ), $match ) ) {
			$form_link = trim( $match[ 'form_link' ] );
			$form->set_link( $form_link );
		}

		// Layout-Template
		if( preg_match( '/layoutTemplate\/read\/id\/(?<layout_template_id>\d+)[^>]+>(?<layout_template_name>[^<]+)/is', self::match_table_value( 'Layout Template', $response[ 'body' ] ), $match ) ) {
			$layout_template_id = intval( $match[ 'layout_template_id' ] );
			$layout_template_name = html_entity_decode( trim( $match[ 'layout_template_name' ] ) );
			$layout_template = $pardot_model_collection->create_layout_template( $layout_template_id );
			$layout_template->set_name( $layout_template_name );
			$form->set_layout_template_id( $layout_template_id );
		}

		// Submit Button Text
		if( self::match_table_value( 'Submit Button Text', $response[ 'body' ], $form_button_text ) ) {
			$form_button_text = html_entity_decode( trim( $form_button_text ) );
			$form->set_button_text( $form_button_text );
		}

		// Redirect Location
		if( preg_match( '/href="(?<form_redirection>[^"]+)/is', self::match_table_value( 'Redirect Location', $response[ 'body' ] ), $match ) ) {
			$form_redirection = html_entity_decode( trim( $match[ 'form_redirection' ] ) );
			$form->set_redirection( $form_redirection );
		}

		// Always Display Form
		if( self::match_table_value( 'Always Display Form', $response[ 'body' ], $form_always_display ) ) {
			$form_always_display = html_entity_decode( trim( $form_always_display ) );
			$form->set_always_display( $form_always_display == 'Enabled' );
		}

		// Tags
		if( preg_match_all( '/tag\/read\/id\/(?<tag_id>\d+)[^>]+>(?<tag_name>[^<]+)/is', self::match_table_value( 'Tags', $response[ 'body' ] ), $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$tag_id = intval( $match[ 'tag_id' ] );
				$tag_name = html_entity_decode( trim( $match[ 'tag_name' ] ) );
				$tag = $pardot_model_collection->create_tag( $tag_id );
				$tag->set_name( $tag_name );
				$form->add_tag_id( $tag_id );
			}
		}

		// Created At
		if( self::match_table_value( 'Created At', $response[ 'body' ], $form_create_date ) ) {
			$form_create_date = html_entity_decode( trim( $form_create_date ) );
			$form->set_create_date( strtotime( $form_create_date ) );
		}

		// Updated At
		if( self::match_table_value( 'Updated At', $response[ 'body' ], $form_edit_date ) ) {
			$form_edit_date = html_entity_decode( trim( $form_edit_date ) );
			$form->set_edit_date( strtotime( $form_edit_date ) );
		}

		// Created By
		if( preg_match( '/user\/read\/id\/(?<user_id>\d+)[^>]+>(?<user_name>[^<]+)/is', self::match_table_value( 'Created By', $response[ 'body' ] ), $match ) ) {
			$user_id = intval( $match[ 'user_id' ] );
			$user_name = html_entity_decode( trim( $match[ 'user_name' ] ) );
			$user = $pardot_model_collection->create_user( $user_id );
			$user->set_name( $user_name );
			$form->set_creator_id( $user_id );
		}

		// Updated By
		if( preg_match( '/user\/read\/id\/(?<user_id>\d+)[^>]+>(?<user_name>[^<]+)/is', self::match_table_value( 'Updated By', $response[ 'body' ] ), $match ) ) {
			$user_id = intval( $match[ 'user_id' ] );
			$user_name = html_entity_decode( trim( $match[ 'user_name' ] ) );
			$user = $pardot_model_collection->create_user( $user_id );
			$user->set_name( $user_name );
			$form->set_editor_id( $user_id );
		}

		// Autoresponder
		if( preg_match( '/emailTemplate\/read\/id\/(?<email_template_id>\d+)[^>]+>(?<email_template_name>[^<]+)/is', self::match_table_value( 'Send autoresponder email', $response[ 'body' ] ), $match ) ) {
			$email_template_id = intval( $match[ 'email_template_id' ] );
			$email_template_name = html_entity_decode( $match[ 'email_template_name' ] );
			$email_template = $pardot_model_collection->create_email_template( $email_template_id );
			$email_template->set_name( $email_template_name );
			$form->set_autoresponder_id( $email_template_id );
		}
	}

	/** @param Pardot_Models\Form &$form */
	private function update_form_wizzard_step_2( &$form ) {
		// Lade die WizzardStep2-Ansicht
		$url = str_replace( [ '%id%', '%page%' ], [ $form->get_id(), 2 ], self::WIZARD_URL );
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_form_wizzard_step_2() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Lade alle Felder
		if( preg_match_all( '/ffData-\d+[^>]+?value="(?<form_field_data>[^"]+?)"/is', $response[ 'body' ], $matches, PREG_SET_ORDER ) ) {
			foreach( $matches as $match ) {
				$form_field_data = json_decode( htmlspecialchars_decode( $match[ 'form_field_data' ] ) );
				if( !empty( $form_field_data ) ) {
					$form_field = new Pardot_Models\Form_Field();
					$form_field->set_sort_order( intval( $form_field_data->sort_order ) );
					$form_field->set_prospect_field_id( intval( $form_field_data->prospect_field_default_id ?: $form_field_data->prospect_field_custom_id ) );
					$form_field->set_name( $form_field_data->name ?: '' );
					$form_field->set_label( $form_field_data->label ?: '' );
					$form_field->set_description( $form_field_data->description ?: '' );
					$form_field->set_error_message( $form_field_data->error_message ?: '' );
					$form_field->set_regular_expression( $form_field_data->regular_expression ?: '' );
					$form_field->set_default_value( $form_field_data->default_value ?: '' );
					$form_field->set_default_mail_merge_value( $form_field_data->default_mail_merge_value ?: '' );
					$form_field->set_css_classes( $form_field_data->css_classes ?: '' );
					$form_field->set_type( intval( $form_field_data->type ) );
					$form_field->set_data_format( intval( $form_field_data->data_format ) );
					$form_field->set_required( $form_field_data->is_required );
					$form_field->set_always_display( $form_field_data->is_always_display );
					$form_field->set_use_conditionals( $form_field_data->is_use_conditionals );
					$form_field->set_use_values( $form_field_data->is_use_values );
					$form_field->set_maintain_initial_value( $form_field_data->is_maintain_initial_value );
					$form_field->set_do_not_prefill( $form_field_data->is_do_not_prefill );
					$form_field->set_enable_geo_enrichment( $form_field_data->enable_geo_enrichment );
					$form_field->set_field_source( $form_field_data->field_source );

					foreach( $form_field_data->form_field_values as $form_field_value_data ) {
						$form_field_value = new Pardot_Models\Form_Field_Value();
						$form_field_value->set_listx_id( intval( $form_field_value_data->listx_id ) );
						$form_field_value->set_profile_id( intval( $form_field_value_data->profile_id ) );
						$form_field_value->set_value( $form_field_value_data->value );
						$form_field_value->set_label( $form_field_value_data->label );
						$form_field->add_form_field_value( $form_field_value );
					}
					foreach( $form_field_data->form_field_conditionals as $form_field_conditional_data ) {
						$form_field_conditional = new Pardot_Models\Form_Field_Conditional();
						$form_field_conditional->set_conditional_id( intval( $form_field_conditional_data->conditional_id ) );
						$form_field_conditional->set_prospect_field_id( intval( $form_field_conditional_data->prospect_field_default_id ?: $form_field_conditional_data->prospect_field_custom_id ) );
						$form_field_conditional->set_sort_order( intval( $form_field_conditional_data->sort_order ) );
						$form_field->add_form_field_conditional( $form_field_conditional );
					}

					$form->add_form_field( $form_field );
				}
			}
		}
	}

	/** @param Pardot_Models\Form &$form */
	private function update_form_wizzard_step_3( &$form ) {
		// Lade die WizzardStep3-Ansicht
		$url = str_replace( [ '%id%', '%page%' ], [ $form->get_id(), 3 ], self::WIZARD_URL );
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_form_wizzard_step_3() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Layout Template
		if( preg_match( '/data-object-id="(?<layout_template_id>\d+).*?object-name.*?>(?<layout_template_name>[^<]*)</is', $response[ 'body' ], $match ) ) {
			$layout_template_id = intval( $match[ 'layout_template_id' ] );
			$layout_template_name = html_entity_decode( trim( $match[ 'layout_template_name' ] ) );
			$layout_template = $pardot_model_collection->create_layout_template( $layout_template_id );
			$layout_template->set_name( $layout_template_name );
			$form->set_layout_template_id( $layout_template_id );
		}

		// Submit Button Text
		if( preg_match( '/id="submit_button_text[^>]*value="(?<submit_button_text>[^"]*)/is', $response[ 'body' ], $match ) ) {
			$submit_button_text = html_entity_decode( trim( $match[ 'submit_button_text' ] ) );
			$form->set_button_text( $submit_button_text );
		}

		// Above Form
		if( preg_match( '/name="before_form_content"[^>]*>(?<before_form_content>[^<]*)/is', $response[ 'body' ], $match ) ) {
			$before_form_content = html_entity_decode( trim( $match[ 'before_form_content' ] ) );
			$form->set_before_form_content( $before_form_content );
		}

		// Below Form
		if( preg_match( '/name="after_form_content"[^>]*>(?<after_form_content>[^<]*)/is', $response[ 'body' ], $match ) ) {
			$after_form_content = html_entity_decode( trim( $match[ 'after_form_content' ] ) );
			$form->set_after_form_content( $after_form_content );
		}

		// Required Field Character
		if( preg_match( '/required_char.+?selected[^>]*>(?<required_char>[^<]*)/is', $response[ 'body' ], $match ) ) {
			$required_char = html_entity_decode( trim( $match[ 'required_char' ] ) );
			$form->set_required_char( $required_char == '*' );
		}

		// Include "Not you?" link to allow visitors to reset the form
		$form->set_show_not_prospect( preg_match( '/id="show_not_prospect[^>]*(?<show_not_prospect>checked)/is', $response[ 'body' ] ) > 0 );
	}

	/** @param Pardot_Models\Form &$form */
	public function update_form_wizzard_step_5( &$form ) {
		// Lade die WizzardStep4-Ansicht
		$url = str_replace( [ '%id%', '%page%' ], [ $form->get_id(), 5 ], self::WIZARD_URL );
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_form_wizzard_step_5() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Completion Actions
		$form->reset_completion_actions();
		if( preg_match( '/Completion Actions<\/h2>.*?<\/table>/is', $response[ 'body' ], $match ) ) {
			if( preg_match_all( '/tr.*?key">(?<key>[^<]*).*?value">(?<value>.*?)<\/td>/is', $match[ 0 ], $sub_matches, PREG_SET_ORDER ) ) {
				foreach( $sub_matches as $sub_match ) {
					$completion_action_key = $sub_match[ 'key' ];
					$completion_action_value = $sub_match[ 'value' ];
					$form->add_completion_action( $completion_action_key, $completion_action_value );
				}
			}
		}
	}

	/** @param Pardot_Models\Form &$form */
	private function update_form_read_report( &$form ) {
		// Lade die ReadReport-Ansicht
		$url = str_replace( '%id%', $form->get_id(), self::REPORT_URL );
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_form_read_report() - Kein Content erhalten', E_USER_WARNING );
			return false;
		}

		// Total Views
		if( self::match_table_value( 'Total Views', $response[ 'body' ], $form_total_views ) ) {
			$form_total_views = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $form_total_views ) ) );
			$form->set_total_views( $form_total_views );
		}

		// Unique Views
		if( preg_match( '/>(?<unique_views>[^<]*)</is', self::match_table_value( 'Unique Views', $response[ 'body' ] ), $match ) ) {
			$form_unique_views = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $match[ 'unique_views' ] ) ) );
			$form->set_unique_views( $form_unique_views );
		}

		// Conversions
		if( preg_match( '/>(?<conversions>[^<]*)</is', self::match_table_value( 'Conversions', $response[ 'body' ] ), $match ) ) {
			$conversions = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $match[ 'conversions' ] ) ) );
			$form->set_conversions( $conversions );
		}

		// Total Submissions
		if( self::match_table_value( 'Total Submissions', $response[ 'body' ], $form_total_submissions ) ) {
			$form_total_submissions = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $form_total_submissions ) ) );
			$form->set_total_submissions( $form_total_submissions );
		}

		// Unique Submissions
		if( preg_match( '/>(?<unique_submissions>[^<]*)</is', self::match_table_value( 'Unique Submissions', $response[ 'body' ] ), $match ) ) {
			$form_unique_submissions = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $match[ 'unique_submissions' ] ) ) );
			$form->set_unique_submissions( $form_unique_submissions );
		}

		// Total Errors
		if( self::match_table_value( 'Total Errors', $response[ 'body' ], $form_total_errors ) ) {
			$form_total_errors = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $form_total_errors ) ) );
			$form->set_total_errors( $form_total_errors );
		}

		// Unique Errors
		if( preg_match( '/>(?<unique_errors>[^<]*)</is', self::match_table_value( 'Unique Errors', $response[ 'body' ] ), $match ) ) {
			$form_unique_errors = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $match[ 'unique_errors' ] ) ) );
			$form->set_unique_errors( $form_unique_errors );
		}

		// Total Clicks
		if( self::match_table_value( 'Total Clicks', $response[ 'body' ], $form_total_clicks ) ) {
			$form_total_clicks = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $form_total_clicks ) ) );
			$form->set_total_clicks( $form_total_clicks );
		}

		// Unique Clicks
		if( preg_match( '/>(?<unique_clicks>[^<]*)</is', self::match_table_value( 'Unique Clicks', $response[ 'body' ] ), $match ) ) {
			$form_unique_clicks = intval( str_replace( [ ',', '.' ], [ '', '' ], trim( $match[ 'unique_clicks' ] ) ) );
			$form->set_unique_views( $form_unique_clicks );
		}
	}

	/** @param Pardot_Models\Email_Template &$email_template */
	private function update_email_template_read( &$email_template ) {
		// Lade die Read-Ansicht
		$url = $email_template->get_read_url();
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		$session = new Pardot_Session();
		$response = $session->http_get( $url );
		if( is_wp_error( $response ) || empty( $response[ 'body' ] ) ) {
			trigger_error( 'Update_Form_Details::update_email_template_read() - Kein Content erhalten', E_USER_WARNING );
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

		// Message
		if( preg_match( '/pre>(?<message>.+?)<\/pre/is', self::match_table_value( 'Message', $response[ 'body' ] ), $match ) ) {
			$email_template_message = trim( html_entity_decode( $match[ 'message' ] ) );
			$email_template->set_message( $email_template_message );
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
}
