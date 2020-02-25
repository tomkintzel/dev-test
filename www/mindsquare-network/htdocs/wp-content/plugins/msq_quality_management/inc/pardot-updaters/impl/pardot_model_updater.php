<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

class Pardot_Model_Updater extends Async_Task_Manager {
	/** @var string OPTION_NAME */
	const OPTION_NAME = 'msq_pardot_model_updater';

	/** */
	public function __construct() {
		parent::__construct( self::OPTION_NAME );
		$this->add_task_runner( 110, Update_Form_Index::class );
		$this->add_task_runner( 120, Update_Form_Details::class );
		$this->add_task_runner( 130, Update_Form_Analyse::class );
		$this->add_task_runner( 210, Update_Email_Template_Index::class );
		$this->add_task_runner( 220, Update_Email_Template_Details::class );
	}

	/**
	 * @param boolean $all_pages
	 */
	public function update_form_index( $all_pages = false ) {
		$this->add_task( Update_Form_Index::class, $all_pages );
	}

	/**
	 * @param int $form_id
	 */
	public function update_form_detail( $form_id ) {
		$this->add_task( Update_Form_Details::class, $form_id );
	}

	/**
	 * @param int $form_id
	 */
	public function update_form_analyse( $form_id ) {
		$this->add_task( Update_Form_Analyse::class, $form_id );
	}

	/**
	 * @param boolean $all_pages
	 */
	public function update_email_template_index( $all_pages = false ) {
		$this->add_task( Update_Email_Template_Index::class, $all_pages );
	}

	/**
	 * @param int $email_template_id
	 */
	public function update_email_template_detail( $email_template_id ) {
		$this->add_task( Update_Email_Template_Details::class, $email_template_id );
	}

	/**
	 * @return Pardot_Model_Updater
	 */
	public static function get_instance() {
		static $instance;
		if( empty( $instance ) ) {
			$instance = new self();
		}
		return $instance;
	}
}
