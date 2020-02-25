<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Models;

/** */
class Form_Field implements Pardot_Model {
	/** @var int $sort_order */
	private $sort_order;

	/** @var int $prospect_field_id */
	private $prospect_field_id;

	/** @var string $name */
	private $name;

	/** @var string $label */
	private $label;

	/** @var string $description */
	private $description;

	/** @var string $error_message */
	private $error_message;

	/** @var string $regular_expression */
	private $regular_expression;

	/** @var string $default_value */
	private $default_value;

	/** @var string $default_mail_merge_value */
	private $default_mail_merge_value;

	/** @var string $css_classes */
	private $css_classes;

	/** @var int $type */
	private $type;

	/** @var int $data_format */
	private $data_format;

	/** @var bool $is_required */
	private $is_required;

	/** @var bool $is_always_display */
	private $is_always_display;

	/** @var bool $is_use_conditionals */
	private $is_use_conditionals;

	/** @var bool $is_use_values */
	private $is_use_values;

	/** @var bool $is_maintain_initial_value */
	private $is_maintain_initial_value;

	/** @var bool $is_do_not_prefill */
	private $is_do_not_prefill;

	/** @var int $enable_geo_enrichment */
	private $enable_geo_enrichment;

	/** @var string $field_source */
	private $field_source;

	/** @var Form_Field_Value[] $form_field_values */
	private $form_field_values = [];

	/** @var Form_Field_Conditional[] $form_field_conditionals */
	private $form_field_conditionals = [];

	/**
	 */
	public function __construct() {
	}

	/** @return int */
	public function get_id() {
		return $this->id;
	}

	/** @return int */
	public function get_sort_order() {
		return $this->sort_order;
	}

	/** @param int $sort_order */
	public function set_sort_order( $sort_order ) {
		$this->sort_order = $sort_order;
	}

	/** @return int */
	public function get_prospect_field_id() {
		return $this->prospect_field_id;
	}

	/** @param int $prospect_field_id */
	public function set_prospect_field_id( $prospect_field_id ) {
		$this->prospect_field_id = $prospect_field_id;
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
	public function get_label() {
		return $this->label;
	}

	/** @param string $label */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/** @return string */
	public function get_description() {
		return $this->description;
	}

	/** @param string $description */
	public function set_description( $description ) {
		$this->description = $description;
	}

	/** @return string */
	public function get_error_message() {
		return $this->error_message;
	}

	/** @param string $error_message */
	public function set_error_message( $error_message ) {
		$this->error_message = $error_message;
	}

	/** @return string */
	public function get_regular_expression() {
		return $this->regular_expression;
	}

	/** @param string $regular_expression */
	public function set_regular_expression( $regular_expression ) {
		$this->regular_expression = $regular_expression;
	}

	/** @return string */
	public function get_default_value() {
		return $this->default_value;
	}

	/** @param string $default_value */
	public function set_default_value( $default_value ) {
		$this->default_value = $default_value;
	}

	/** @return string */
	public function get_default_mail_merge_value() {
		return $this->default_mail_merge_value;
	}

	/** @param string $default_mail_merge_value */
	public function set_default_mail_merge_value( $default_mail_merge_value ) {
		$this->default_mail_merge_value = $default_mail_merge_value;
	}

	/** @return string */
	public function get_css_classes() {
		return $this->css_classes;
	}

	/** @param string $css_classes */
	public function set_css_classes( $css_classes ) {
		$this->css_classes = $css_classes;
	}

	/** @return int */
	public function get_type() {
		return $this->type;
	}

	/** @param int $type */
	public function set_type( $type ) {
		$this->type = $type;
	}

	/** @return int */
	public function get_data_format() {
		return $this->data_format;
	}

	/** @param int $data_format */
	public function set_data_format( $data_format ) {
		$this->data_format = $data_format;
	}

	/** @return bool */
	public function is_required() {
		return $this->is_required;
	}

	/** @param bool $is_required */
	public function set_required( $is_required ) {
		$this->is_required = $is_required;
	}

	/** @return bool */
	public function is_always_display() {
		return $this->is_always_display;
	}

	/** @param bool $is_always_display */
	public function set_always_display( $is_always_display ) {
		$this->is_always_display = $is_always_display;
	}

	/** @return bool */
	public function is_use_conditionals() {
		return $this->is_use_conditionals;
	}

	/** @param bool $is_use_conditionals */
	public function set_use_conditionals( $is_use_conditionals ) {
		$this->is_use_conditionals = $is_use_conditionals;
	}

	/** @return bool */
	public function is_use_values() {
		return $this->is_use_values;
	}

	/** @param bool $is_use_values */
	public function set_use_values( $is_use_values ) {
		$this->is_use_values = $is_use_values;
	}

	/** @return bool */
	public function is_maintain_initial_value() {
		return $this->is_maintain_initial_value;
	}

	/** @param bool $is_maintain_initial_value */
	public function set_maintain_initial_value( $is_maintain_initial_value ) {
		$this->is_maintain_initial_value = $is_maintain_initial_value;
	}

	/** @return bool */
	public function is_do_not_prefill() {
		return $this->is_do_not_prefill;
	}

	/** @param bool $is_do_not_prefill */
	public function set_do_not_prefill( $is_do_not_prefill ) {
		$this->is_do_not_prefill = $is_do_not_prefill;
	}

	/** @return int */
	public function get_enable_geo_enrichment() {
		return $this->enable_geo_enrichment;
	}

	/** @param int $enable_geo_enrichment */
	public function set_enable_geo_enrichment( $enable_geo_enrichment ) {
		$this->enable_geo_enrichment = $enable_geo_enrichment;
	}

	/** @return string */
	public function get_field_source() {
		return $this->field_source;
	}

	/** @param string $field_source */
	public function set_field_source( $field_source ) {
		$this->field_source = $field_source;
	}

	/** @return Form_Field_Value[] */
	public function get_form_field_values() {
		return $this->form_field_values;
	}

	/** @param Form_Field_Value $form_field_value */
	public function add_form_field_value( $form_field_value ) {
		$this->form_field_values[] = $form_field_value;
	}

	/** @return Form_Field_Conditional[] */
	public function get_form_field_conditionals() {
		return $this->form_field_conditionals;
	}

	/** @param Form_Field_Conditional $form_field_conditional */
	public function add_form_field_conditional( $form_field_conditional ) {
		$this->form_field_conditionals[] = $form_field_conditional;
	}
}
