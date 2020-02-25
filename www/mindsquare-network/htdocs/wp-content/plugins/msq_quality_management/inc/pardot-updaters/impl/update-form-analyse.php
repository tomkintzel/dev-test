<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;
use MSQ\Plugin\Quality_Management\Columns\Dataset;
use MSQ\Plugin\Quality_Management\Quality_Management;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;

class Update_Form_Analyse extends Async_Task {
	/** @var string COLUMN_NAME_ERROR */
	private const COLUMN_NAME_ERROR = 'errors';

	/** @param int $form_id */
	public function execute( $form_id ) {
		// vars
		$scope = Quality_Management::get_instance()->get_scope( 'form' );
		$column_collection = $scope->get_column_collection();
		$pardot_model_collection = Pardot_Model_Collection::get_instance();

		// Erstelle ein Iterator
		$forms = [ $pardot_model_collection->get_form( $form_id ) ];
		$dataset = new Dataset();
		$dataset->set_column_collection( $column_collection );
		$dataset->set_values( $forms );
		$iterator = $dataset->select_all();

		// Führe ein Update aus
		$this->reset( $iterator );
		$this->update_custom_values( $iterator );
		$this->update_custom_errors( $iterator );

		// Zeitstempel aktualisieren
		$iterator->rewind();
		while( $iterator->valid() ) {
			$form = $iterator->current();
			$form->set_update_analyse_date( current_time( 'timestamp' ) );
			// Nachstes Element
			$iterator->next();
		}

		// Speichere die Änderungen
		$pardot_model_collection->save();
	}

	/**
	 * @param Dataset_Iterator $iterator
	 */
	private function reset( $iterator ) {
		// Setzte den Iterator zurück
		$iterator->rewind();

		while( $iterator->valid() ) {
			// Setzte die Custom-Werte zurück
			$form = $iterator->current();
			$form->reset_meta_values();

			// Nachstes Element
			$iterator->next();
		}
	}

	/**
	 * @param Dataset_Iterator $iterator
	 */
	private function update_custom_values( $iterator ) {
		// vars
		$ruleset_groups = get_field( 'qs-ruleset-changes', 'option' );

		// Setzte den Iterator zurück
		$iterator->rewind();

		// Wenn Regeln gefunden wurden
		if( !empty( $ruleset_groups ) ) {
			while( $iterator->valid() ) {
				// Gespeicherte Regeln anwenden
				foreach( $ruleset_groups as $ruleset_group ) {
					$activate_trigger_group = $ruleset_group[ 'activate-trigger-group' ];
					$trigger_group = $ruleset_group[ 'trigger-group' ];

					// Wenn die Bedinfung für die Gruppe zurtifft
					if( $activate_trigger_group === false || $trigger_group->match( $iterator ) ) {
						$rules = $ruleset_group[ 'rules' ];

						foreach( $rules as $rule ) {
							$trigger = $rule[ 'trigger' ];
							$changes = $rule[ 'changes' ];

							// Wenn die Bedingung für die Regel zutrifft
							if( $trigger->match( $iterator ) ) {
								// Übernehme alle Änderungen
								foreach( $changes as $change ) {
									$fieldname = $change[ 'qs-ruleset-values_field' ];
									$value = $change[ 'value' ];
									$iterator->set_value( $fieldname, $value );
								}
							}
						}
					}
				}

				/**
				 * Hier können eigene Werteänderungen mittels PHP erzeugt werden.
				 *
				 * @param array[] $changes = [
				 *     @param string fieldname
				 *     @param string value
				 * ]
				 * @param MSQ\Plugin\Quality_Management\Pardot_Models\Form $form
				 */
				$changes = apply_filters( 'msq/plugins/quality_management/update_custom_values', [], $iterator->current() );
				if( !empty( $changes ) ) {
					foreach( $changes as $change ) {
						$fieldname = sanitize_title( 'custom_' . $change[ 'fieldname' ] );
						$value = $change[ 'value' ];
						$iterator->set_value( $fieldname, $value );
					}
				}

				// Nachstes Element
				$iterator->next();
			}
		}
	}

	/**
	 * @param Dataset_Iterator $iterator
	 */
	private function update_custom_errors( $iterator ) {
		// vars
		$ruleset_groups = get_field( 'qs-ruleset-errors', 'option' );

		// Setzte den Iterator zurück
		$iterator->rewind();

		// Wenn Regeln gefunden wurden
		if( !empty( $ruleset_groups ) ) {
			while( $iterator->valid() ) {
				$errors = [];

				foreach( $ruleset_groups as $ruleset_group ) {
					$activate_trigger_group = $ruleset_group[ 'activate-trigger-group' ];
					$trigger_group = $ruleset_group[ 'trigger-group' ];

					// Wenn die Bedinfung für die Gruppe zurtifft
					if( $activate_trigger_group === false || $trigger_group->match( $iterator ) ) {
						$rules = $ruleset_group[ 'errors' ];
						$group_name = $ruleset_group[ 'name' ];

						foreach( $rules as $rule ) {
							// vars
							$name = $rule[ 'name' ];
							$trigger = $rule[ 'trigger' ];
							$message = $rule[ 'message' ];
							$level = $rule[ 'level' ];

							// Wenn die Bedingung zutrifft, dann soll eine Fehlermeldung hinzugefügt werden
							if( $trigger->match( $iterator ) ) {
								$errors[ $level ][] = sprintf( '<b>%s</b><div class="QMErrorColumn-Details"><p>%s</p><pre>%s</pre></div>', $message, sprintf( '%s > %s', $group_name, $name ), $trigger->debug( $iterator ) );
							}
						}

						// Wenn Fehlermeldungen gefunden wurden
						if( !empty( $errors ) ) {
							$iterator->set_value( self::COLUMN_NAME_ERROR, $errors );
						}
					}
				}

				// Nachstes Element
				$iterator->next();
			}
		}
	}
}
