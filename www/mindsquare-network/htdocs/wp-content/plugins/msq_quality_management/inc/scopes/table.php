<?php
namespace MSQ\Plugin\Quality_Management\Scopes;
use MSQ\Plugin\Quality_Management\Pardot_Updaters\Pardot_Model_Updater;
use MSQ\Plugin\Quality_Management\Quality_Management;
use WP_List_Table;

/**
 * @todo Diese Klasse verstößt gegen Open-Closed-Prinzip.
 * Da ein WP_List_Table mit einem späteren Hook erstellt werden kann,
 * musste die Reihenfolge der Ausführungen verändert werden.
 */
abstract class Table extends WP_List_Table {
	/** @var string $page_slug */
	protected $page_slug;

	/** @var string $page_title */
	protected $page_title;

	/** @var Scope $scope */
	protected $scope;

	/** @var string[] $founded_column_names */
	protected $founded_column_names;

	/**
	 * @param string $page_slug
	 * @param string $page_title
	 */
	public function __construct( $page_slug, $page_title, $scope ) {
		// vars
		$this->page_slug = $page_slug;
		$this->page_title = $page_title;
		$this->scope = $scope;

		// Initialisieren die Tabelle
		parent::__construct( [
			'singular' => $this->get_page_title(),
			'ajax' => false
		]);

		// Registere neue Scripts und Styles
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/** */
	public function enqueue_styles() {
		// Select2
		$url = Quality_Management::get_instance()->get_url();
		wp_enqueue_style( 'css-qs-select2', $url . 'assets/js/select2@4.0.13/select2.min.css', [], '4.0.13' );
		wp_enqueue_script( 'js-qs-select2', $url . 'assets/js/select2@4.0.13/select2.full.js', [], '4.0.13' );
	}

	/** */
	abstract public function load_page();

	/** */
	public function display_page() {
		$this->prepare_items();
		$pardot_model_updater = Pardot_Model_Updater::get_instance();
		?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php echo $this->get_page_title(); ?></h1>
				<hr class="wp-header-end">
				<?php $pardot_model_updater->display(); ?>
				<form method="GET">
					<input type="hidden" name="page" value="<?php echo $this->get_page_slug(); ?>" />
					<?php
						$this->search_box( 'Suchen', $this->get_page_slug() );
					?>
				</form>
				<form method="GET">
					<input type="hidden" name="page" value="<?php echo $this->get_page_slug(); ?>" />
					<?php
						$this->add_hidden_fields( [ 'orderby', 'order', 'post_mime_type', 'detached', 'searchIn', 'search' ] );
						$this->display();
					?>
				</form>
			</div>
		<?php
	}

	/**
	 * @param array $fields
	 */
	protected function add_hidden_fields( $fields ) {
		foreach( $fields as $field ) {
			if( !empty( $_REQUEST[ $field ] ) ) {
				printf( '<input type="hidden" name="%s" value="%s" />', $field, $_REQUEST[ $field ] );
			}
		}
	}

	/**
	 * Displays the search box.
	 *
	 * @since 3.1.0
	 *
	 * @param string $text     The 'submit' button label.
	 * @param string $input_id ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['search'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		$this->add_hidden_fields( [ 'orderby', 'order', 'post_mime_type', 'detached' ] );

		$scope = $this->get_scope();
		$column_collection = $scope->get_column_collection();
		$column_groups = $column_collection->get_column_groups();
		?>
			<p class="search-box QMTable-SearchBox">
				<select name="searchIn" class="QMTable-SearchIn" id="QMTable-SearchIn">
					<option value="" <?php echo empty( $_REQUEST[ 'searchIn' ] ) ? 'selected="selected"' : ''; ?>>Alle</option>
					<?php foreach( $column_groups as $group => $columns ): ?>
						<optgroup label="<?php echo $group; ?>">
							<?php foreach( $columns as $column_name => $column ): ?>
								<option value="<?php echo $column_name; ?>" <?php echo !empty( $_REQUEST[ 'searchIn' ] ) && $_REQUEST[ 'searchIn' ] == $column_name ? 'selected="selected"' : ''; ?>><?php echo $group . ' > ' . $column->get_headline(); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
				<script>jQuery(function() {jQuery('#QMTable-SearchIn').select2();});</script>
				<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
				<input type="search" class="QMTable-Search" id="<?php echo esc_attr( $input_id ); ?>" name="search" value="<?php echo isset( $_REQUEST[ 'search' ] ) ? esc_attr( wp_unslash( $_REQUEST[ 'search' ] ) ) : ''; ?>" />
				<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
			</p>
		<?php
	}

	/** */
	public function display() {
		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );

		$this->display_table();

		$this->display_tablenav( 'bottom' );
	}

	/** */
	public function display_table() {
		$singular = $this->_args['singular'];
		?>
			<div class="QMTable">
				<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
					<thead>
						<tr>
							<?php $this->print_column_headers(); ?>
						</tr>
					</thead>
					<tbody id="the-list" <?php if( $singular )echo " data-wp-lists='list:$singular'"; ?>>
						<?php $this->display_rows_or_placeholder(); ?>
					</tbody>
					<tfoot>
						<tr>
							<?php $this->print_column_headers( false ); ?>
						</tr>
					</tfoot>
				</table>
				<script>
					window.addEventListener('DOMContentLoaded', function() {
						var modal_fields = document.querySelectorAll('.QMTable .thickbox');
						[].forEach.call(modal_fields, function(modal_field) {
							modal_field.addEventListener('click', function() {
								var active_modal_field = document.querySelectorAll('.QMTable-focusedRow');
								[].forEach.call(active_modal_field, function(active_modal_field) {
									active_modal_field.classList.remove('QMTable-focusedRow');
								});
								this.closest('tr').classList.add('QMTable-focusedRow');
							});
						});
					});
				</script>
			</div>
		<?php
	}

	/**
	 * @param Dataset $dataset
	 * @return Dataset_Iterator
	 */
	public function search_items( $dataset ) {
		$items = [];
		if( !empty( $_GET[ 'search' ] ) ) {
			$search = $_GET[ 'search' ];
			$searchIn = !empty( $_GET[ 'searchIn' ] ) ? $_GET[ 'searchIn' ] : null;
			$items = $dataset->select( function( $item ) use( $search, $searchIn ) {
				if( !empty( $searchIn ) ) {
					$column_values = $item->get_value( $searchIn );
				}
				if( !empty( $column_values ) ) {
					if( !empty( $column_values ) ) {
						foreach( (array)$column_values as $column_value ) {
							if( is_array( $column_value ) ) {
								foreach( (array) $column_value as $column_sub_value ) {
									if( stripos( $column_sub_value, $search ) !== false ) {
										$this->founded_column_names[ $searchIn ] = $searchIn;
										return true;
									}
								}
							} else if( stripos( $column_value, $search ) !== false ) {
								$this->founded_column_names[ $searchIn ] = $searchIn;
								return true;
							}
						}
					}
				} else {
					$columns = $item->get_values();
					foreach( (array)$columns as $column_name => $column_values ) {
						foreach( (array)$column_values as $column_value ) {
							if( is_array( $column_value ) ) {
								foreach( (array) $column_value as $column_sub_value ) {
									if( stripos( $column_sub_value, $search ) !== false ) {
										$this->founded_column_names[ $column_name ] = $column_name;
										return true;
									}
								}
							} else if( stripos( $column_value, $search ) !== false ) {
								$this->founded_column_names[ $column_name ] = $column_name;
								return true;
							}
						}
					}
				}
				return false;
			});
		} else {
			$items = $dataset->select_all();
		}
		return $items;
	}

	/** @return string */
	public function get_page_slug() {
		return $this->page_slug;
	}

	/** @return string */
	public function get_page_title() {
		return $this->page_title;
	}

	/** @return Scope */
	public function get_scope() {
		return $this->scope;
	}

	/**
	 * @param string $action
	 * @param Closure $callback
	 */
	protected static function add_action( $action, $callback ) {
		if( did_action( $action ) ) {
			call_user_func( $callback );
		} else {
			add_action( $action, $callback );
		}
	}
}
