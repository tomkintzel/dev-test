<?php
namespace MSQ\Plugin\Quality_Management\Scopes\Form;
use MSQ\Plugin\Quality_Management\Columns\Dataset;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Async_Task;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Model_Updater;
use MSQ\Plugin\Quality_Management\Pardot_Models\Pardot_Model_Collection;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Async_Task_Manager;

/** */
class Table extends \MSQ\Plugin\Quality_Management\Scopes\Table {
	/** */
	public function load_page() {
		// Optionen für die Seite hinzufügen
		add_screen_option( 'per_page', [
			'label' => 'Anzahl Formulare',
			'default' => 50,
			'option' => 'forms_per_page'
		]);

		// Hooks definieren
		$this->define_admin_hooks();

		// Führe die Aktionen aus
		$action = $this->current_action();
		if( !empty( $action ) ) {
			$this->process_actions( $action );
		}
	}

	/** */
	public function define_admin_hooks() {
		add_filter( 'default_hidden_columns', [ $this, 'get_default_hidden_columns' ], 10, 2 );
		add_filter( 'hidden_columns', [ $this, 'get_hidden_columns' ], 10, 2 );
		add_thickbox();
	}

	/**
	 * @param string $action
	 */
	public function process_actions( $action ) {
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		if( $action == 'update' && !empty( $_REQUEST[ 'form' ] ) ) {
			$form_ids = $_REQUEST[ 'form' ];
			foreach( $form_ids as $form_id ) {
				$pardot_model_updater->update_form_detail( $form_id );
			}
			$pardot_model_updater->save();
			$pardot_model_updater->launch();
		} else if( $action == 'eval' && !empty( $_REQUEST[ 'form' ] ) ) {
			$form_ids = $_REQUEST[ 'form' ];
			foreach( $form_ids as $form_id ) {
				$pardot_model_updater->update_form_analyse( $form_id );
			}
			$pardot_model_updater->save();
			$pardot_model_updater->launch();
		} else if( $action == 'eval_all' ) {
			$forms = Pardot_Model_Collection::get_instance()->get_forms();
			foreach( $forms as $form ) {
				$pardot_model_updater->update_form_analyse( $form->get_id() );
			}
			$pardot_model_updater->save();
			$pardot_model_updater->launch();
		} else if( $action == 'update_all' ) {
			$pardot_model_updater->update_form_index( isset( $_POST[ 'all_pages' ] ) );
			$pardot_model_updater->save();
			$pardot_model_updater->launch();
		} else if( $action == 'reset_all' ) {
			$pardot_model_collection = Pardot_Model_Collection::get_instance();
			$pardot_model_collection->reset();
			$pardot_model_collection->save();
			$pardot_model_updater->update_form_index();
			$pardot_model_updater->save();
			$pardot_model_updater->launch();
		} else if( $action == 'cancel' ) {
			$pardot_model_updater->cancel();
		}

		// Auf die Tabelle umleiten
		$admin_url = preg_replace( '/(\?|&)action2?=[^&]*/', '$1', $_SERVER[ 'REQUEST_URI' ] );
		if( wp_redirect( $admin_url ) ) {
			exit();
		}
	}

	/**
	 * Erstellt eine Liste von Formularen.
	 */
	public function prepare_items() {
		// Initialisieren die Daten
		$scope = $this->get_scope();
		$column_collection = $scope->get_column_collection();
		$dataset = new Dataset();
		$dataset->set_column_collection( $column_collection );
		$dataset->set_values( Pardot_Model_Collection::get_instance()->get_forms() );

		// Suchen und Sortieren
		$items = $this->search_items( $dataset );
		$this->sort_items( $items );

		// Entferne die Entwicklungsformulare
		$items->filter( function( $value ) {
			return $value->get_meta_value( 'custom_fachbereich' ) !== 'Entwicklung';
		});

		// Pagination
		$per_page = $this->get_items_per_page( 'forms_per_page' );
		$current_page = $this->get_pagenum();
		$this->set_pagination_args( [
			'total_items' => count( $items ),
			'per_page' => $per_page
		]);
		$items->slice( $current_page, $per_page );

		// Erstelle den Header
		$this->_column_headers = $this->get_column_info();

		// Erzeuge ein Iterator
		$this->items = $items;
	}

	/**
	 * @param Dataset_Iterator $items
	 */
	public function sort_items( $items ) {
		$orderby = $_GET[ 'orderby' ] ?? 'errors';
		$order = ( $_GET[ 'order' ] ?? 'desc' ) == 'asc' ? SORT_ASC : SORT_DESC;
		$items->sort([
			$orderby => $order
		]);
	}
	
	/** */
	public function display_rows() {
		while( $this->items->valid() ) {
			$this->single_row( $this->items );
			$this->items->next();
		}
	}

	/**
	 * Erzeugt die Ausgabe eines Columns
	 *
	 * @param Dataset_Iterator $item
	 * @param string $column_name
	 * @return string[]
	 */
	public function column_default( $item, $column_name ) {
		return $item->get_output( $column_name );
	}

	/**
	 * Erzeugt eine Liste von Columns die standardmäßig nicht angezeigt werden.
	 * @param string[] $hidden
	 * @param WP_Screen $screen
	 * @return string[]
	 */
	public function get_default_hidden_columns( $hidden, $screen ) {
		if( $this->screen->id == $screen->id ) {
			$scope = $this->get_scope();
			$column_collection = $scope->get_column_collection();
			return array_unique( array_merge( $hidden, array_keys( $column_collection->get_hidden_columns() ) ) );
		}
		return $hidden;
	}

	/**
	 * @param string[] $hidden
	 * @param WP_Screen $screen
	 * @return string[]
	 */
	public function get_hidden_columns( $hidden, $screen ) {
		if( $this->screen->id == $screen->id && !empty( $this->founded_column_names ) ) {
			$hidden = array_diff( $hidden, array_values( $this->founded_column_names ) );
		}
		return $hidden;
	}

	/**
	 * Erzeugt eine Liste von Columns die sortiert werden können.
	 * @return string[]
	 */
	public function get_sortable_columns() {
		// vars
		$sortable = [];
		$scope = $this->get_scope();
		$column_collection = $scope->get_column_collection();
		$columns = $column_collection->get_columns();

		// Füge alle Felder hinzu
		foreach( $columns as $column ) {
			$sortable[ $column->get_name() ] = [ $column->get_name(), false ];
		}

		// Standardmäßig wird nach dem Datum absteigend sortiert
		$sortable[ 'errors' ] = [ 'errors', false ];
		return $sortable;
	}

	/**
	 * Erzeugt eine Liste von Headers.
	 * @return string[]
	 **/
	public function get_columns() {
		// vars
		$headers = [
			'cb' => '<input type="checkbox" />'
		];
		$scope = $this->get_scope();
		$column_collection = $scope->get_column_collection();
		$column_groups = $column_collection->get_column_groups();

		// Füge alle Felder hinzu
		foreach( $column_groups as $group_name => $columns ) {
			foreach( $columns as $column ) {
				$headers[ $column->get_name() ] = '<b>' . $group_name . '</b>&nbsp;&gt; <br />' . $column->get_headline();
			}
		}
		return $headers;
	}

	/**
	 * Diese Funktion überschreibt den primäre Spaltennamen.
	 * @return string
	 */
	protected function get_default_primary_column_name() {
		return 'form_name';
	}

	/**
	 * Steuert die Checkbox-Funktion
	 * @param Iterator $item
	 */
	public function column_cb( $item ) {
		$form_id = $item->get_value( 'form_id' );
		?>
			<input id="cb-select-<?php echo $form_id; ?>" type="checkbox" name="form[]" value="<?php echo $form_id; ?>" />
		<?php
	}

	/**
	 * Diese Funktion fügt beim primären Spaltennamen einige Links hinzu.
	 * @param Iterator $item
	 */
	function column_form_name( $item ) {
		// vars
		$form = $item->current();
		$actions[ 'edit' ] = sprintf( '<a href="%s">Formular Bearbeiten</a>', $form->get_read_url() );
		$pardot_model_updater = Pardot_Model_Updater::get_instance();

		// Wenn der Updater gerade keine Aufgaben macht
		if( !$pardot_model_updater->is_locked() ) {
			$actions[ 'eval' ] = sprintf( '<a href="%s">Neu auswerten</a>', add_query_arg( [ 'form' => [ $form->get_id() ], 'action' => 'eval' ], $_SERVER[ 'REQUEST_URI' ] ) );
			$actions[ 'update' ] = sprintf( '<a href="%s">Änderungen laden</a>', add_query_arg( [ 'form' => [ $form->get_id() ], 'action' => 'update' ], $_SERVER[ 'REQUEST_URI' ] ) );
		}

		// Ausgabe
		echo sprintf( '%s %s', $item->get_output( 'form_name' ), $this->row_actions( $actions ) );
	}

	/**
	 * Diese Funktion fügt die Bulk-Aktionen hinzu.
	 * @return string[]
	 */
	protected function get_bulk_actions() {
		$pardot_model_updater = Pardot_Model_Updater::get_instance();

		// Wenn der Updater gerade keine Aufgaben macht
		if( !$pardot_model_updater->is_locked() ) {
			return [
				'eval'   => 'Neu auswerten',
				'update' => 'Änderungen laden'
			];
		}
		return [];
	}

	/**
	 * Diese Fehlermeldung wird angezeigt, wenn keine Formulare hinzugefügt wurden.
	 */
	public function no_items() {
		echo 'Keine Formulare gefunden';
	}

	/**
	 * Hier werden zusätzliche Funktionen zum Header hinzugefügt.
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		if( !$pardot_model_updater->is_locked() ) {
			$actions = [
				'eval_all'   => sprintf( '<a class="button-primary QMTable-Button" href="%s" title="Diese Aktion geht erneut durch alle Formulare und wendet die Regeln an">Neu auswerten</a>', add_query_arg( [ 'action' => 'eval_all' ] ) ),
				'update_all' => sprintf( '<a class="button-primary QMTable-Button" href="%s" title="Diese Aktion lädt die neuesten Änderungen von Pardot herunter">Änderungen laden</a>', add_query_arg( [ 'action' => 'update_all' ] ) )
			];
		} else {
			$actions = [
				'cancel' => sprintf( '<a class="button QMTable-Button" href="%s" title="Bricht die aktuelle Aktion ab">Abbrechen</a>', add_query_arg( [ 'action' => 'cancel' ] ) )
			];
		}
		if( !empty( $actions ) ): ?>
			<div class="alignleft actions">
				<?php foreach( $actions as $action_name => $action_text ): ?>
					<span class="<?php echo $action_name; ?>"><?php echo $action_text; ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif;
	}
}
