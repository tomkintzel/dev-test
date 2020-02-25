<?php
namespace MSQ\Plugin\Quality_Management\Scopes\Email_Template;
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
		self::init_email_template_columns( $column_collection );
		self::init_email_template_foreign_columns( $column_collection );
		return $column_collection;
	}

	/** @param Column_Collection &$column_collection */
	private static function init_email_template_columns( &$column_collection ) {
		$email_template_id = new Column( 'email_template_id', 'ID', true );
		$email_template_id->set_value_callback( self::get_callback( 'get_id' ) );
		$column_collection->add_column( $email_template_id, 'Email-Template' );

		$email_template_name = new Column( 'email_template_name', 'Name' );
		$email_template_name->set_value_callback( self::get_callback( 'get_name' ) );
		$email_template_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url' ) );
		$column_collection->add_column( $email_template_name, 'Email-Template' );

		/** @todo: senders */
		/** @todo: replies */

		$email_template_subject = new Column( 'email_template_subject', 'Betreff' );
		$email_template_subject->set_value_callback( self::get_callback( 'get_subject' ) );
		$column_collection->add_column( $email_template_subject, 'Email-Template' );

		$email_template_message = new Column( 'email_template_message', 'Nachricht' );
		$email_template_message->set_value_callback( self::get_callback( 'get_message' ) );
		$email_template_message->set_output_callback( self::get_modal_callback( 'get_message', $email_template_message->get_headline() ) );
		$column_collection->add_column( $email_template_message, 'Email-Template' );

		/** @todo: availables */

		$email_template_create_date = new Column( 'email_template_create_date', 'Erstellt am', true );
		$email_template_create_date->set_value_callback( self::get_callback( 'get_create_date' ) );
		$email_template_create_date->set_output_callback( self::get_date_callback( 'get_create_date', 'get_creator' ) );
		$column_collection->add_column( $email_template_create_date, 'Email-Template' );

		$email_template_edit_date = new Column( 'email_template_edit_date', 'Bearbeitet am', true );
		$email_template_edit_date->set_value_callback( self::get_callback( 'get_edit_date' ) );
		$email_template_edit_date->set_output_callback( self::get_date_callback( 'get_edit_date', 'get_editor' ) );
		$column_collection->add_column( $email_template_edit_date, 'Email-Template' );

		$email_template_update_index_date = new Column( 'email_template_update_index_date', 'Index abgerufen am' );
		$email_template_update_index_date->set_value_callback( self::get_callback( 'get_update_index_date' ) );
		$email_template_update_index_date->set_output_callback( self::get_date_callback( 'get_update_index_date', null ) );
		$column_collection->add_column( $email_template_update_index_date, 'Email-Template' );

		$email_template_update_details_date = new Column( 'email_template_update_details_date', 'Details abgerufen am' );
		$email_template_update_details_date->set_value_callback( self::get_callback( 'get_update_details_date' ) );
		$email_template_update_details_date->set_output_callback( self::get_date_callback( 'get_update_details_date', null ) );
		$column_collection->add_column( $email_template_update_details_date, 'Email-Template' );
	}

	/** @param Column_Collection &$column_collection */
	private static function init_email_template_foreign_columns( &$column_collection ) {
		// Folder
		$email_template_folder_id = new Column( 'email_template_folder_id', 'ID', true );
		$email_template_folder_id->set_value_callback( self::get_callback( 'get_folder_id' ) );
		$column_collection->add_column( $email_template_folder_id, 'Email-Template > Ordner' );

		$email_template_folder_name = new Column( 'email_template_folder_name', 'Name', true );
		$email_template_folder_name->set_value_callback( self::get_callback( 'get_name', [ 'get_folder' ] ) );
		$email_template_folder_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_folder' ] ) );
		$column_collection->add_column( $email_template_folder_name, 'Email-Template > Ordner' );

		// Ersteller
		$email_template_creator_id = new Column( 'email_template_creator_id', 'ID', true );
		$email_template_creator_id->set_value_callback( self::get_callback( 'get_creator_id' ) );
		$column_collection->add_column( $email_template_creator_id, 'Email-Template > Ersteller' );

		$email_template_creator_name = new Column( 'email_template_creator_name', 'Name', true );
		$email_template_creator_name->set_value_callback( self::get_callback( 'get_name', [ 'get_creator' ] ) );
		$email_template_creator_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_creator' ] ) );
		$column_collection->add_column( $email_template_creator_name, 'Email-Template > Ersteller' );

		// Bearbeiter
		$email_template_editor_id = new Column( 'email_template_editor_id', 'ID', true );
		$email_template_editor_id->set_value_callback( self::get_callback( 'get_editor_id' ) );
		$column_collection->add_column( $email_template_editor_id, 'Email-Template > Bearbeiter' );

		$email_template_editor_name = new Column( 'email_template_editor_name', 'Name', true );
		$email_template_editor_name->set_value_callback( self::get_callback( 'get_name', [ 'get_editor' ] ) );
		$email_template_editor_name->set_output_callback( self::get_link_callback( 'get_name', 'get_read_url', [ 'get_editor' ] ) );
		$column_collection->add_column( $email_template_editor_name, 'Email-Template > Bearbeiter' );
	}
}
