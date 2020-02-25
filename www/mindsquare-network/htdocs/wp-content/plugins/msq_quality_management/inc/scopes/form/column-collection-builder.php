<?php
namespace MSQ\Plugin\Quality_Management\Scopes\Form;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;
use MSQ\Plugin\Quality_Management\Columns\Column_Collection;
use MSQ\Plugin\Quality_Management\Columns\Column;

/** */
class Column_Collection_Builder extends \MSQ\Plugin\Quality_Management\Scopes\Column_Collection_Builder {
	/** */
	public static function build() {
		// vars
		$column_collection = new Column_Collection();

		// init
		self::init_form_columns( $column_collection );
		self::init_form_statistic_columns( $column_collection );
		self::init_form_style_columns( $column_collection );
		self::init_form_foreign_columns( $column_collection );
		self::init_form_field_columns( $column_collection );
		self::init_form_completion_columns( $column_collection );
		self::init_autoresponder_columns( $column_collection );
		self::init_autoresponder_foreign_columns( $column_collection );
		self::init_custom_columns( $column_collection );
		self::init_error_column( $column_collection );
		return $column_collection;
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_columns( &$column_collection ) {
		$form_id = new Column( 'form_id', 'ID', true );
		$form_id->set_value_callback( self::get_callback( 'get_id' ) );
		$column_collection->add_column( $form_id, 'Formular' );

		$form_name = new Column( 'form_name', 'Name' );
		$form_name->set_value_callback( self::get_callback( 'get_name' ) );
		$form_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url' ) );
		$column_collection->add_column( $form_name, 'Formular' );

		$form_link = new Column( 'form_link', 'IFrame-Link', true );
		$form_link->set_value_callback( self::get_callback( 'get_link' ) );
		$form_link->set_output_callback( self::get_link_callback( 'get_link', 'get_link' ) );
		$column_collection->add_column( $form_link, 'Formular' );

		$form_button_text = new Column( 'form_button_text', 'Submit-Text', true );
		$form_button_text->set_value_callback( self::get_callback( 'get_button_text' ) );
		$form_button_text->set_output_callback( self::get_link_callback( 'get_button_text', 'get_redirection' ) );
		$column_collection->add_column( $form_button_text, 'Formular' );

		$form_redirection = new Column( 'form_redirection', 'Weiterleitung', true );
		$form_redirection->set_value_callback( self::get_callback( 'get_redirection' ) );
		$form_redirection->set_output_callback( self::get_link_callback( 'get_redirection', 'get_redirection' ) );
		$column_collection->add_column( $form_redirection, 'Formular' );

		$form_always_display = new Column( 'form_always_display', 'Immer anzeigen' );
		$form_always_display->set_value_callback( self::get_callback( 'get_always_display' ) );
		$form_always_display->set_output_callback( self::get_bool_callback( 'get_always_display' ) );
		$column_collection->add_column( $form_always_display, 'Formular' );

		$form_create_date = new Column( 'form_create_date', 'Erstellt am' );
		$form_create_date->set_value_callback( self::get_callback( 'get_create_date' ) );
		$form_create_date->set_output_callback( self::get_date_callback( 'get_create_date', 'get_creator' ) );
		$column_collection->add_column( $form_create_date, 'Formular' );

		$form_edit_date = new Column( 'form_edit_date', 'Bearbeitet am' );
		$form_edit_date->set_value_callback( self::get_callback( 'get_edit_date' ) );
		$form_edit_date->set_output_callback( self::get_date_callback( 'get_edit_date', 'get_editor' ) );
		$column_collection->add_column( $form_edit_date, 'Formular' );

		$form_update_index_date = new Column( 'form_update_index_date', 'Index abgerufen am' );
		$form_update_index_date->set_value_callback( self::get_callback( 'get_update_index_date' ) );
		$form_update_index_date->set_output_callback( self::get_date_callback( 'get_update_index_date', null ) );
		$column_collection->add_column( $form_update_index_date, 'Formular' );

		$form_update_details_date = new Column( 'form_update_details_date', 'Details abgerufen am' );
		$form_update_details_date->set_value_callback( self::get_callback( 'get_update_details_date' ) );
		$form_update_details_date->set_output_callback( self::get_date_callback( 'get_update_details_date', null ) );
		$column_collection->add_column( $form_update_details_date, 'Formular' );

		$form_update_analyse_date = new Column( 'form_update_anayse_date', 'Analysiert am' );
		$form_update_analyse_date->set_value_callback( self::get_callback( 'get_update_analyse_date' ) );
		$form_update_analyse_date->set_output_callback( self::get_date_callback( 'get_update_analyse_date', null ) );
		$column_collection->add_column( $form_update_analyse_date, 'Formular' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_statistic_columns( &$column_collection ) {
		$form_total_views = new Column( 'form_total_views', 'Aufrufe', true );
		$form_total_views->set_value_callback( self::get_callback( 'get_total_views' ) );
		$column_collection->add_column( $form_total_views, 'Statistik' );

		$form_unique_views = new Column( 'form_unique_views', 'Eindeutige Aufrufe', true );
		$form_unique_views->set_value_callback( self::get_callback( 'get_unique_views' ) );
		$column_collection->add_column( $form_unique_views, 'Statistik' );

		$form_conversions = new Column( 'form_conversions', 'Conversions', true );
		$form_conversions->set_value_callback( self::get_callback( 'get_conversions' ) );
		$column_collection->add_column( $form_conversions, 'Statistik' );

		$form_total_submissions = new Column( 'form_total_submissions', 'Form-Submissions', true );
		$form_total_submissions->set_value_callback( self::get_callback( 'get_total_submissions' ) );
		$column_collection->add_column( $form_total_submissions, 'Statistik' );

		$form_unique_submissions = new Column( 'form_unique_submissions', 'Eindeutige Form-Submissions', true );
		$form_unique_submissions->set_value_callback( self::get_callback( 'get_unique_submissions' ) );
		$column_collection->add_column( $form_unique_submissions, 'Statistik' );

		$form_total_errors = new Column( 'form_total_errors', 'Fehler', true );
		$form_total_errors->set_value_callback( self::get_callback( 'get_total_errors' ) );
		$column_collection->add_column( $form_total_errors, 'Statistik' );

		$form_unique_errors = new Column( 'form_unique_errors', 'Eindeutige Fehler', true );
		$form_unique_errors->set_value_callback( self::get_callback( 'get_unique_errors' ) );
		$column_collection->add_column( $form_unique_errors, 'Statistik' );

		$form_total_clicks = new Column( 'form_total_clicks', 'Klicks', true );
		$form_total_clicks->set_value_callback( self::get_callback( 'get_total_clicks' ) );
		$column_collection->add_column( $form_total_clicks, 'Statistik' );

		$form_unique_clicks = new Column( 'form_unique_clicks', 'Eindeutige Klicks', true );
		$form_unique_clicks->set_value_callback( self::get_callback( 'get_unique_clicks' ) );
		$column_collection->add_column( $form_unique_clicks, 'Statistik' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_style_columns( &$column_collection ) {
		$form_before_form_content = new Column( 'form_before_form_content', 'Vor dem Formular', true );
		$form_before_form_content->set_value_callback( self::get_callback( 'get_before_form_content' ) );
		$form_before_form_content->set_output_callback( self::get_modal_callback( 'get_before_form_content', $form_before_form_content->get_headline() ) );
		$column_collection->add_column( $form_before_form_content, 'Style' );

		$form_after_form_content = new Column( 'form_after_form_content', 'Nach dem Formular', true );
		$form_after_form_content->set_value_callback( self::get_callback( 'get_after_form_content' ) );
		$form_after_form_content->set_output_callback( self::get_modal_callback( 'get_after_form_content', $form_after_form_content->get_headline() ) );
		$column_collection->add_column( $form_after_form_content, 'Style' );

		$form_required_char = new Column( 'form_required_char', 'Zeichen für Erforderliche Felder', true );
		$form_required_char->set_value_callback( self::get_callback( 'get_required_char' ) );
		$form_required_char->set_output_callback( self::get_bool_callback( 'get_required_char' ) );
		$column_collection->add_column( $form_required_char, 'Style' );

		$form_show_not_prospect = new Column( 'form_show_not_prospect', '"Nicht du"-Link', true );
		$form_show_not_prospect->set_value_callback( self::get_callback( 'get_show_not_prospect' ) );
		$form_show_not_prospect->set_output_callback( self::get_bool_callback( 'get_show_not_prospect' ) );
		$column_collection->add_column( $form_show_not_prospect, 'Style' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_foreign_columns( &$column_collection ) {
		// Folder
		$folder_id = new Column( 'folder_id', 'ID', true );
		$folder_id->set_value_callback( self::get_callback( 'get_folder_id' ) );
		$column_collection->add_column( $folder_id, 'Ordner' );

		$folder_name = new Column( 'folder_name', 'Name', true );
		$folder_name->set_value_callback( self::get_callback( 'get_name', 'get_folder' ) );
		$folder_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_folder' ] ) );
		$column_collection->add_column( $folder_name, 'Ordner' );

		// Kampagne
		$campaign_id = new Column( 'campaign_id', 'ID', true );
		$campaign_id->set_value_callback( self::get_callback( 'get_campaign_id' ) );
		$column_collection->add_column( $campaign_id, 'Kampagne' );

		$campaign_name = new Column( 'campaign_name', 'Name', true );
		$campaign_name->set_value_callback( self::get_callback( 'get_name', [ 'get_campaign' ] ) );
		$campaign_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_campaign' ] ) );
		$column_collection->add_column( $campaign_name, 'Kampagne' );

		// Tracking-Domain
		$tracking_domain_id = new Column( 'tracking_domain_id', 'ID', true );
		$tracking_domain_id->set_value_callback( self::get_callback( 'get_tracking_domain_id' ) );
		$column_collection->add_column( $tracking_domain_id, 'Tracking Domain' );

		$tracking_domain_name = new Column( 'tracking_domain_name', 'Name', true );
		$tracking_domain_name->set_value_callback( self::get_callback( 'get_name', [ 'get_tracking_domain' ] ) );
		$tracking_domain_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_tracking_domain' ] ) );
		$column_collection->add_column( $tracking_domain_name, 'Tracking Domain' );

		// Layout-Template
		$layout_template_id = new Column( 'layout_template_id', 'ID', true );
		$layout_template_id->set_value_callback( self::get_callback( 'get_layout_template_id' ) );
		$column_collection->add_column( $layout_template_id, 'Layout Template' );

		$layout_template_name = new Column( 'layout_template_name', 'Name' );
		$layout_template_name->set_value_callback( self::get_callback( 'get_name', [ 'get_layout_template' ] ) );
		$layout_template_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_layout_template' ] ) );
		$column_collection->add_column( $layout_template_name, 'Layout Template' );

		/** @todo: tags */

		// Ersteller
		$creator_id = new Column( 'creator_id', 'ID', true );
		$creator_id->set_value_callback( self::get_callback( 'get_creator_id' ) );
		$column_collection->add_column( $creator_id, 'Ersteller' );

		$creator_name = new Column( 'creator_name', 'Name', true );
		$creator_name->set_value_callback( self::get_callback( 'get_name', [ 'get_creator' ] ) );
		$creator_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_creator' ] ) );
		$column_collection->add_column( $creator_name, 'Ersteller' );

		// Bearbeiter
		$editor_id = new Column( 'editor_id', 'ID', true );
		$editor_id->set_value_callback( self::get_callback( 'get_editor_id' ) );
		$column_collection->add_column( $editor_id, 'Bearbeiter' );

		$editor_name = new Column( 'editor_name', 'Name', true );
		$editor_name->set_value_callback( self::get_callback( 'get_name', [ 'get_editor' ] ) );
		$editor_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_editor' ] ) );
		$column_collection->add_column( $editor_name, 'Bearbeiter' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_field_columns( &$column_collection ) {
		// vars
		$forms = Pardot_Model_Collection::get_instance()->get_forms();
		$fields = [];

		// Sammel alle möglichen Felder
		foreach( $forms as $form ) {
			$form_fields = $form->get_form_fields();
			foreach( $form_fields as $form_field ) {
				$prospect_field_id = $form_field->get_prospect_field_id();
				if( empty( $fields[ $prospect_field_id ] ) ) {
					$fields[ $prospect_field_id ] = $form_field;
				}
			}
		}

		// Erstelle für jedes Form-Field ein eigenes Column
		foreach( $fields as $prospect_field_id => $field ) {
			// vars
			$field_label = $field->get_label();

			$form_field_id = new Column( 'form_field_' . $prospect_field_id . '_id', 'ID', true );
			$form_field_id->set_value_callback( self::get_callback( 'get_prospect_field_id', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_id, "Felder > $field_label" );

			$form_field_name = new Column( 'form_field_' . $prospect_field_id . '_name', 'Name', true );
			$form_field_name->set_value_callback( self::get_callback( 'get_label', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_name, "Felder > $field_label" );

			$form_field_type = new Column( 'form_field_' . $prospect_field_id . '_type', 'Type', true );
			$form_field_type->set_value_callback( self::get_callback( 'get_type', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_types = [
				1 => 'Text',
				2 => 'Radio Button',
				3 => 'Checkbox',
				4 => 'Dropdown',
				5 => 'Textarea',
				7 => 'Hidden',
				12 => 'Date'
			];
			$form_field_type->set_output_callback( function( $form ) use ( $prospect_field_id, $form_field_types ) {
				$form_field = $form->get_form_field( $prospect_field_id );
				if( !empty( $form_field ) ) {
					$type = $form_field->get_type();
					if( !empty( $form_field_types[ $type ] ) ) {
						return $form_field_types[ $type ];
					}
				}
				return '';
			});
			$column_collection->add_column( $form_field_type, "Felder > $field_label" );

			$form_field_data_format = new Column( 'form_field_' . $prospect_field_id . '_data_format', 'Data Format', true );
			$form_field_data_format->set_value_callback( self::get_callback( 'get_data_format', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_data_formats = [
				1 => 'Text',
				2 => 'Number',
				3 => 'Email',
				4 => 'Email with valid mail server',
				5 => 'Email not from ISPs and free email providers'
			];
			$form_field_data_format->set_output_callback( function( $form ) use ( $prospect_field_id, $form_field_data_formats ) {
				$form_field = $form->get_form_field( $prospect_field_id );
				if( !empty( $form_field ) ) {
					$type = $form_field->get_data_format();
					if( !empty( $form_field_data_formats[ $type ] ) ) {
						return $form_field_data_formats[ $type ];
					}
				}
				return '';
			});
			$column_collection->add_column( $form_field_data_format, "Felder > $field_label" );

			$form_field_required = new Column( 'form_field_' . $prospect_field_id . '_required', 'Pflichtfeld', true );
			$form_field_required->set_value_callback( self::get_callback( 'is_required', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_required->set_output_callback( self::get_bool_callback( 'is_required', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_required, "Felder > $field_label" );

			$form_field_always_display = new Column( 'form_field_' . $prospect_field_id . '_always_display', 'Immer anzeigen', true );
			$form_field_always_display->set_value_callback( self::get_callback( 'is_always_display', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_always_display->set_output_callback( self::get_bool_callback( 'is_always_display', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_always_display, "Felder > $field_label" );

			$form_field_do_not_prefill = new Column( 'form_field_' . $prospect_field_id . '_do_not_prefill', 'Nicht vorausgefüllt', true );
			$form_field_do_not_prefill->set_value_callback( self::get_callback( 'is_do_not_prefill', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_do_not_prefill->set_output_callback( self::get_bool_callback( 'is_do_not_prefill', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_do_not_prefill, "Felder > $field_label" );

			$form_field_maintain_initial_value = new Column( 'form_field_' . $prospect_field_id . '_maintain_initial_value', 'Initialwert beibehalten', true );
			$form_field_maintain_initial_value->set_value_callback( self::get_callback( 'is_maintain_initial_value', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$form_field_maintain_initial_value->set_output_callback( self::get_bool_callback( 'is_maintain_initial_value', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_maintain_initial_value, "Felder > $field_label" );

			$form_field_error_message = new Column( 'form_field_' . $prospect_field_id . '_error_message', 'Fehlermeldung', true );
			$form_field_error_message->set_value_callback( self::get_callback( 'get_error_message', [ 'get_form_field' ], [ $prospect_field_id ] ) );
			$column_collection->add_column( $form_field_error_message, "Felder > $field_label" );

			$form_field_conditionals = new Column( 'form_field_' . $prospect_field_id . '_conditionals', 'Progressive Felder', true );
			$form_field_conditionals->set_value_callback( function( $form ) use( $prospect_field_id ) {
				$form_field = $form->get_form_field( $prospect_field_id );
				if( !empty( $form_field ) ) {
					$conditionals = $form_field->get_form_field_conditionals();

					if( !empty( $conditionals ) ) {
						$conditionals = array_map( function( $conditional ) {
							return $conditional->get_prospect_field_id();
						}, $conditionals );
						return implode( ', ', $conditionals );
					}
				}
				return '';
			});
			$column_collection->add_column( $form_field_conditionals, "Felder > $field_label" );
		}
	}

	/** @param Column_Collection &$column_collection */
	private static function init_form_completion_columns( &$column_collection ) {
		// vars
		$forms = Pardot_Model_Collection::get_instance()->get_forms();
		$completion_actions = [];

		// Sammel alle möglichen Felder
		foreach( $forms as $form ) {
			$raw_completion_actions = $form->get_completion_actions();
			foreach( $raw_completion_actions as $raw_completion_action_key => $raw_completion_action_values ) {
				if( !in_array( $raw_completion_action_key, $completion_actions ) ) {
					$completion_actions[] = $raw_completion_action_key;
				}
			}
		}
	
		// Erstelle für jedes Form-Field ein eigenes Column
		foreach( $completion_actions as $completion_action_key ) {
			$column_name = sanitize_title( 'completion_action_' . $completion_action_key );
			$completion_action_column = new Column( $column_name, $completion_action_key, true );
			$completion_action_column->set_value_callback( function( $form ) use( $completion_action_key ) {
				$completion_action = $form->get_completion_action( $completion_action_key );
				if( !empty( $completion_action ) ) {
					return implode( ', ', $completion_action );
				}
				return '';
			});
			$completion_action_column->set_output_callback( function( $form ) use( $completion_action_key ) {
				$completion_action = $form->get_completion_action( $completion_action_key );
				if( !empty( $completion_action ) ) {
					return implode( '<br /> ', $completion_action );
				}
				return '';
			});
			$column_collection->add_column( $completion_action_column, "Completion-Actions" );
		}
	}

	/** @param Column_Collection &$column_collection */
	private static function init_autoresponder_columns( &$column_collection ) {
		$autoresponder_id = new Column( 'autoresponder_id', 'ID', true );
		$autoresponder_id->set_value_callback( self::get_callback( 'get_autoresponder_id' ) );
		$column_collection->add_column( $autoresponder_id, 'Autoresponder' );

		$autoresponder_name = new Column( 'autoresponder_name', 'Name' );
		$autoresponder_name->set_value_callback( self::get_callback( 'get_name', [ 'get_autoresponder' ] ) );
		$autoresponder_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_name, 'Autoresponder' );

		/** @todo: senders */
		/** @todo: replies */

		$autoresponder_subject = new Column( 'autoresponder_subject', 'Betreff' );
		$autoresponder_subject->set_value_callback( self::get_callback( 'get_subject', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_subject, 'Autoresponder' );

		$autoresponder_message = new Column( 'autoresponder_message', 'Nachricht' );
		$autoresponder_message->set_value_callback( self::get_callback( 'get_message', [ 'get_autoresponder' ] ) );
		$autoresponder_message->set_output_callback( self::get_modal_callback( 'get_message', $autoresponder_message->get_headline(), [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_message, 'Autoresponder' );

		/** @todo: availables */

		$autoresponder_create_date = new Column( 'autoresponder_create_date', 'Erstellt am', true );
		$autoresponder_create_date->set_value_callback( self::get_callback( 'get_create_date', [ 'get_autoresponder' ] ) );
		$autoresponder_create_date->set_output_callback( self::get_date_callback( 'get_create_date', 'get_creator', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_create_date, 'Autoresponder' );

		$autoresponder_edit_date = new Column( 'autoresponder_edit_date', 'Bearbeitet am', true );
		$autoresponder_edit_date->set_value_callback( self::get_callback( 'get_edit_date', [ 'get_autoresponder' ] ) );
		$autoresponder_edit_date->set_output_callback( self::get_date_callback( 'get_edit_date', 'get_editor', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_edit_date, 'Autoresponder' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_autoresponder_foreign_columns( &$column_collection ) {
		// Folder
		$autoresponder_folder_id = new Column( 'autoresponder_folder_id', 'ID', true );
		$autoresponder_folder_id->set_value_callback( self::get_callback( 'get_folder_id', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_folder_id, 'Autoresponder > Ordner' );

		$autoresponder_folder_name = new Column( 'autoresponder_folder_name', 'Name', true );
		$autoresponder_folder_name->set_value_callback( self::get_callback( 'get_name', [ 'get_autoresponder', 'get_folder' ] ) );
		$autoresponder_folder_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_autoresponder', 'get_folder' ] ) );
		$column_collection->add_column( $autoresponder_folder_name, 'Autoresponder > Ordner' );

		// Ersteller
		$autoresponder_creator_id = new Column( 'autoresponder_creator_id', 'ID', true );
		$autoresponder_creator_id->set_value_callback( self::get_callback( 'get_creator_id', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_creator_id, 'Autoresponder > Ersteller' );

		$autoresponder_creator_name = new Column( 'autoresponder_creator_name', 'Name', true );
		$autoresponder_creator_name->set_value_callback( self::get_callback( 'get_name', [ 'get_autoresponder', 'get_creator' ] ) );
		$autoresponder_creator_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_autoresponder', 'get_creator' ] ) );
		$column_collection->add_column( $autoresponder_creator_name, 'Autoresponder > Ersteller' );

		// Bearbeiter
		$autoresponder_editor_id = new Column( 'autoresponder_editor_id', 'ID', true );
		$autoresponder_editor_id->set_value_callback( self::get_callback( 'get_editor_id', [ 'get_autoresponder' ] ) );
		$column_collection->add_column( $autoresponder_editor_id, 'Autoresponder > Bearbeiter' );

		$autoresponder_editor_name = new Column( 'autoresponder_editor_name', 'Name', true );
		$autoresponder_editor_name->set_value_callback( self::get_callback( 'get_name', [ 'get_autoresponder', 'get_editor' ] ) );
		$autoresponder_editor_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_autoresponder', 'get_editor' ] ) );
		$column_collection->add_column( $autoresponder_editor_name, 'Autoresponder > Bearbeiter' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_custom_columns( &$column_collection ) {
		$custom_columns = get_field( 'qs-ruleset-columns', 'option' );

		/**
		 * Dieser Filter ermöglicht das Hinzufügen von eigenen Felder mittels PHP.
		 *
		 * @param array $custom_columns[] = [
		 *     @param string fieldname
		 *     @param string[] settings = [
		 *         @param boolean hidden
		 *     ]
		 * ]
		 */
		$custom_columns = apply_filters( 'msq/plugins/quality_management/register_custom_values', $custom_columns );
		if( !empty( $custom_columns ) ) {
			foreach( $custom_columns as $custom_column ) {
				$headline = $custom_column[ 'headline' ] ?: $custom_column[ 'fieldname' ];
				$fieldname = $custom_column[ 'fieldname' ] ?: $custom_column[ 'headline' ];
				$fieldname = sanitize_title( 'custom_' . $fieldname );
				$column = new Column( $fieldname, $headline );
				if( is_array( $custom_column[ 'settings' ] ) ) {
					if( in_array( 'hidden', $custom_column[ 'settings' ] ) ) {
						$column->set_hidden( true );
					}
				}
				$column->set_value_callback( function( $form ) use( $fieldname ) {
					if( !empty( $form ) ) {
						return $form->get_meta_value( $fieldname );
					} else {
						return '';
					}
				});
				$column_collection->add_column( $column, 'Eigene Felder' );
			}
		}
	}

	/** @param Column_Collection &$column_collection */
	private static function init_error_column( &$column_collection ) {
		$name = 'errors';
		$headline = 'Fehlermeldungen';

		$column = new Column( $name, $headline );
		$column->set_value_callback( function( $form ) use( $name ) {
			if( !empty( $form ) ) {
				$errors = $form->get_meta_value( $name );
				return $errors;
			}
			return [];
		});

		$column->set_output_callback( function( $form ) use( $name ) {
			if( !empty( $form ) ) {
				// vars
				$errors = $form->get_meta_value( $name );

				// Signifikanter Fehler
				if( is_array( $errors ) ) {
					// vars
					$log_levels = array_keys( $errors );
					$max_log_level = max( $log_levels );
					$inline_id = sprintf( 'modal-%s-ErrorColumn', $form->get_id() );
					$form_name = $form->get_name();
					$form_link = $form->get_read_url();

					// Sortiere die Fehlermeldungen
					array_multisort( $log_levels, SORT_DESC, $errors );
					$errors = array_combine( $log_levels, $errors );

					// Erstelle den Inhalt
					$column_content = [];
					$modal_content = [
						sprintf( '<div class="QMErrorColumn-FormInfo"><p>Formular: <a href="%s">%s</a></p>', $form_link, $form_name )
					];
					$log_level_labels = [ 'Sonstige', 'Warnungen', 'Fehler' ];
					foreach( $errors as $log_level => $messages ) {
						$message_content = [];
						foreach( $messages as $message ) {
							$message_content[] = sprintf( '<li class="QMErrorColumn-Message QMErrorColumn-level%s" onclick="this.classList.toggle(\'QMErrorColumn-active\');">%s</li>', $log_level, $message );
						}

						$column_content[] = sprintf( '<div class="QMErrorColumn-CountMessages QMErrorColumn-level%s">%s: %s</div>', $log_level, $log_level_labels[ $log_level ], count( $messages ) );
						$modal_content[] = sprintf( '<ul class="QMErrorColumn-MessageGroup">%s</ul>', implode( '', $message_content ) );
					}
					$modal_content = implode( '', $modal_content );
					$column_content[] = sprintf( '<a href="#TB_inline?&width=750&height=700&inlineId=%s" class="thickbox" title="Fehlermeldungen">ansehen</a><div id="%s" class="modal fade" style="display: none"><div class="QMErrorColumn-Modal">%s</div></div>', $inline_id, $inline_id, $modal_content );
					$column_content = implode( '', $column_content );

					// Erzeuge das Modal-Feld
					$content = sprintf( '<div class="QMErrorColumn-ErrorColumn QMErrorColumn-maxLevel%s">%s</div>', $max_log_level, $column_content );
					return $content;
				}
			}
			return '';
		});

		/**
		 * @param string[][] $values
		 * @param int $sort_direction
		 * @param string[] $items
		 */
		$column->set_sort_callback( function( $values, $sort_direction, &$items ) {
			// vars
			$sort_levels = [];

			// Formatiere von  $values[ item_key ][ log_level ][] = message  -->  $sort_levels[ $log_level ][ $item_key ] = count( messages )
			foreach( (array)$values as $item_key => $item ) {
				if( is_array( $item ) ) {
					foreach( $item as $log_level => $messages ) {
						$sort_levels[ $log_level ][ $item_key ] = count( $messages );
					}
				}
			}

			// Fehlende Werte auffüllen
			foreach( (array)$sort_levels as $log_level => $sort_values ) {
				foreach( $values as $item_key => $item ) {
					if( empty( $sort_levels[ $log_level ][ $item_key ] ) ) {
						$sort_levels[ $log_level ][ $item_key ] = 0;
					}
				}
			}

			// Sortiere
			$args = [];
			krsort( $sort_levels );
			foreach( $sort_levels as $log_level => $sort_values ) {
				ksort( $sort_values );
				$args[] = $sort_values;
				$args[] = $sort_direction;
			}
			$args[] = &$items;
			return call_user_func_array( 'array_multisort', $args );
		});
		$column_collection->add_column( $column, 'Prüfung' );
	}
}
