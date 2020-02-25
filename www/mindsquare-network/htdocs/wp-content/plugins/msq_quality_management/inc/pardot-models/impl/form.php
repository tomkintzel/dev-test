<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Model_Updater;

/** */
class Form implements Pardot_Model {
	/** @var int $id */
	private $id;

	/** @var string $name */
	private $name;

	/** @var string $link */
	private $link;

	/** @var string $link */
	private $button_text;

	/** @var string $redirection */
	private $redirection;

	/** @var bool always_display */
	private $always_display;

	/** @var int $create_date */
	private $create_date;

	/** @var int $edit_date */
	private $edit_date;

	/** @var int $update_index_date */
	private $update_index_date;

	/** @var int $update_details_date */
	private $update_details_date;

	/** @var int $update_analyse_date */
	private $update_analyse_date;

	/** @var int $total_views */
	private $total_views;

	/** @var int $unique_views */
	private $unique_views;

	/** @var int $conversions */
	private $conversions;

	/** @var int $total_submissions */
	private $total_submissions;

	/** @var int $unique_submissions */
	private $unique_submissions;

	/** @var int $total_errors */
	private $total_errors;

	/** @var int $unique_errors */
	private $unique_errors;

	/** @var int $total_clicks */
	private $total_clicks;

	/** @var int $unique_clicks */
	private $unique_clicks;

	/** @var string $before_form_content */
	private $before_form_content;

	/** @var string $after_form_content */
	private $after_form_content;

	/** @var bool $required_char */
	private $required_char;

	/** @var bool $show_not_prospect */
	private $show_not_prospect;

	/** @var int $folder_id */
	private $folder_id;

	/** @var int $campaign_id */
	private $campaign_id;

	/** @var int $tracking_domain_id */
	private $tracking_domain_id;

	/** @var int $layout_template_id */
	private $layout_template_id;

	/** @var int[] $tags */
	private $tags = [];

	/** @var int $creator_id */
	private $creator_id;

	/** @var int $editor_id */
	private $editor_id;

	/** @var int $autoresponder_id */
	private $autoresponder_id;

	/** @var Form_Field[] $form_fields */
	private $form_fields = [];

	/** @var string[][] $completion_actions */
	private $completion_actions = [];

	/** @var string[] $meta_values */
	private $meta_values = [];

	/** @var string READ_URL */
	const READ_URL = 'https://pi.pardot.com/form/read/id/%id%';

	/** @param int $id */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/** @return int */
	public function get_id() {
		return $this->id;
	}

	/** @return string */
	public function get_name() {
		return $this->name;
	}

	/** @param string $name */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/** @return string */
	public function get_link() {
		return $this->link;
	}

	/** @param string $link */
	public function set_link( $link ) {
		$this->link = $link;
	}

	/** @return string */
	public function get_button_text() {
		return $this->button_text;
	}

	/** @param string $button_text */
	public function set_button_text( $button_text ) {
		$this->button_text = $button_text;
	}

	/** @return string */
	public function get_redirection() {
		return $this->redirection;
	}

	/** @param string $redirection */
	public function set_redirection( $redirection ) {
		$this->redirection = $redirection;
	}

	/** @return bool */
	public function get_always_display() {
		return $this->always_display;
	}

	/** @param bool $always_display */
	public function set_always_display( $always_display ) {
		$this->always_display = $always_display;
	}

	/** @return int */
	public function get_create_date() {
		return $this->create_date;
	}

	/** @param int $create_date */
	public function set_create_date( $create_date ) {
		$this->create_date = $create_date;
	}

	/** @return int */
	public function get_edit_date() {
		return $this->edit_date;
	}

	/** @param int $edit_date */
	public function set_edit_date( $edit_date ) {
		$this->edit_date = $edit_date;
	}

	/** @return int */
	public function get_update_index_date() {
		return $this->update_index_date;
	}

	/** @param int $update_details_date */
	public function set_update_index_date( $update_index_date ) {
		$this->update_index_date = $update_index_date;
	}

	/** @return int */
	public function get_update_details_date() {
		return $this->update_details_date;
	}

	/** @param int $update_details_date */
	public function set_update_details_date( $update_details_date ) {
		$this->update_details_date = $update_details_date;
	}

	/** @return int */
	public function get_update_analyse_date() {
		return $this->update_analyse_date;
	}

	/** @param int $update_analyse_date */
	public function set_update_analyse_date( $update_analyse_date ) {
		$this->update_analyse_date = $update_analyse_date;
	}

	/** @return int */
	public function get_total_views() {
		return $this->total_views;
	}

	/** @param int $total_views */
	public function set_total_views( $total_views ) {
		$this->total_views = $total_views;
	}

	/** @return int */
	public function get_unique_views() {
		return $this->unique_views;
	}

	/** @param int $unique_views */
	public function set_unique_views( $unique_views ) {
		$this->unique_views = $unique_views;
	}

	/** @return int */
	public function get_conversions() {
		return $this->conversions;
	}

	/** @param int $conversions */
	public function set_conversions( $conversions ) {
		$this->conversions = $conversions;
	}

	/** @return int */
	public function get_total_submissions() {
		return $this->total_submissions;
	}

	/** @param int $total_submissions */
	public function set_total_submissions( $total_submissions ) {
		$this->total_submissions = $total_submissions;
	}

	/** @return int */
	public function get_unique_submissions() {
		return $this->unique_submissions;
	}

	/** @param int $unique_submissions */
	public function set_unique_submissions( $unique_submissions ) {
		$this->unique_submissions = $unique_submissions;
	}

	/** @return int */
	public function get_total_errors() {
		return $this->total_errors;
	}

	/** @param int $total_errors */
	public function set_total_errors( $total_errors ) {
		$this->total_errors = $total_errors;
	}

	/** @return int */
	public function get_unique_errors() {
		return $this->unique_errors;
	}

	/** @param int $unique_errors */
	public function set_unique_errors( $unique_errors ) {
		$this->unique_errors = $unique_errors;
	}

	/** @return int */
	public function get_total_clicks() {
		return $this->total_clicks;
	}

	/** @param int $total_clicks */
	public function set_total_clicks( $total_clicks ) {
		$this->total_clicks = $total_clicks;
	}

	/** @return int */
	public function get_unique_clicks() {
		return $this->unique_clicks;
	}

	/** @param int $unique_clicks */
	public function set_unique_clicks( $unique_clicks ) {
		$this->unique_clicks = $unique_clicks;
	}

	/** @return string */
	public function get_before_form_content() {
		return $this->before_form_content;
	}

	/** @param string $before_form_content */
	public function set_before_form_content( $before_form_content ) {
		$this->before_form_content = $before_form_content;
	}

	/** @return string */
	public function get_after_form_content() {
		return $this->after_form_content;
	}

	/** @param string $after_form_content */
	public function set_after_form_content( $after_form_content ) {
		$this->after_form_content = $after_form_content;
	}

	/** @return bool */
	public function get_required_char() {
		return $this->required_char;
	}

	/** @param bool $required_char */
	public function set_required_char( $required_char ) {
		$this->required_char = $required_char;
	}

	/** @return bool */
	public function get_show_not_prospect() {
		return $this->show_not_prospect;
	}

	/** @param bool $show_not_prospect */
	public function set_show_not_prospect( $show_not_prospect ) {
		$this->show_not_prospect = $show_not_prospect;
	}

	/** @return int */
	public function get_folder_id() {
		return $this->folder_id;
	}

	/**
	 * @return Folder
	 **/
	public function get_folder() {
		$folder_id = $this->get_folder_id();
		if( !empty( $folder_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_folder( $folder_id );
		}
		return null;
	}

	/** @param int $folder_id */
	public function set_folder_id( $folder_id ) {
		$this->folder_id = $folder_id;
	}

	/** @return int */
	public function get_campaign_id() {
		return $this->campaign_id;
	}

	/**
	 * @return Campaign
	 **/
	public function get_campaign() {
		$campaign_id = $this->get_campaign_id();
		if( !empty( $campaign_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_campaign( $campaign_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_campaign_id( $campaign_id ) {
		$this->campaign_id = $campaign_id;
	}

	/** @return int */
	public function get_tracking_domain_id() {
		return $this->tracking_domain_id;
	}

	/**
	 * @return Tracking_Domain
	 **/
	public function get_tracking_domain() {
		$tracking_domain_id = $this->get_tracking_domain_id();
		if( !empty( $tracking_domain_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_tracking_domain( $tracking_domain_id );
		}
		return null;
	}

	/** @param int $tracking_domain_id */
	public function set_tracking_domain_id( $tracking_domain_id ) {
		$this->tracking_domain_id = $tracking_domain_id;
	}

	/** @return int */
	public function get_layout_template_id() {
		return $this->layout_template_id;
	}

	/**
	 * @return Layout_Template
	 **/
	public function get_layout_template() {
		$layout_template_id = $this->get_layout_template_id();
		if( !empty( $layout_template_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_layout_template( $layout_template_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_layout_template_id( $layout_template_id ) {
		$this->layout_template_id = $layout_template_id;
	}

	/** @return int[] */
	public function get_tag_ids() {
		return $this->tags;
	}

	/**
	 * @return Tag[]
	 **/
	public function get_tags() {
		$pardot_model_collection = Pardot_Model_Collection::get_instance();
		return array_map( function( $tag_id ) use( $pardot_model_collection ) {
			return $pardot_model_collection->get_tag( $tag_id );
		}, $this->tags );
	}

	/** @param int $tag_id */
	public function add_tag_id( $tag_id ) {
		if( !empty( $this->tags[ $tag_id ] ) ) {
			$this->tags[ $tag_id ] = $tag_id;
		}
	}

	/** @return int */
	public function get_creator_id() {
		return $this->creator_id;
	}

	/**
	 * @return User
	 **/
	public function get_creator() {
		$user_id = $this->get_creator_id();
		if( !empty( $user_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_user( $user_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_creator_id( $creator_id ) {
		$this->creator_id = $creator_id;
	}

	/** @return int */
	public function get_editor_id() {
		return $this->editor_id;
	}

	/**
	 * @return User
	 **/
	public function get_editor() {
		$editor_id = $this->get_editor_id();
		if( !empty( $editor_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_user( $editor_id );
		}
		return null;
	}

	/** @param int $campaign_id */
	public function set_editor_id( $editor_id ) {
		$this->editor_id = $editor_id;
	}

	/** @return int */
	public function get_autoresponder_id() {
		return $this->autoresponder_id;
	}

	/**
	 * @return Email_Template
	 **/
	public function get_autoresponder() {
		$autoresponder_id = $this->get_autoresponder_id();
		if( !empty( $autoresponder_id ) ) {
			return Pardot_Model_Collection::get_instance()->get_email_template( $autoresponder_id );
		}
		return null;
	}

	/** @param int $autoresponder_email_template_id */
	public function set_autoresponder_id( $autoresponder_id ) {
		$this->autoresponder_id = $autoresponder_id;
	}

	/** @return Form_Field[] $form_fields */
	public function get_form_fields() {
		return $this->form_fields;
	}

	/**
	 * @param int $prospect_field_id
	 * @return Form_Field
	 **/
	public function get_form_field( $prospect_field_id ) {
		if( !empty( $this->form_fields[ $prospect_field_id ] ) ) {
			return $this->form_fields[ $prospect_field_id ];
		}
		return null;
	}

	/** @param Form_Field $form_field */
	public function add_form_field( $form_field ) {
		$prospect_field_id = $form_field->get_prospect_field_id();
		if( !empty( $prospect_field_id ) ) {
			$this->form_fields[ $prospect_field_id ] = $form_field;
		}
	}

	/** @return string */
	public function get_read_url() {
		return str_replace( '%id%', $this->id, self::READ_URL );
	}

	/** */
	public function reset_completion_actions() {
		$this->completion_actions = [];
	}

	/** @return string[][] */
	public function get_completion_actions() {
		return $this->completion_actions;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public function get_completion_action( $key ) {
		return $this->completion_actions[ $key ] ?? '';
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function add_completion_action( $key, $value ) {
		$this->completion_actions[ $key ][] = $value;
	}

	/** */
	public function reset_meta_values() {
		$this->meta_values = [];
	}

	/**
	 * @param string $meta_key
	 * @param string $meta_value
	 */
	public function set_meta_value( $meta_key, $meta_value ) {
		$this->meta_values[ $meta_key ] = $meta_value;
	}

	/**
	 * @param string $meta_key
	 * @return string
	 */
	public function get_meta_value( $meta_key ) {
		return $this->meta_values[ $meta_key ] ?? '';
	}
}
