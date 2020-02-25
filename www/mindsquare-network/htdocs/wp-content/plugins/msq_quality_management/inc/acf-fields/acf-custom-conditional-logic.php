<?php
namespace MSQ\Plugin\Quality_Management\ACF_Fields;
use MSQ\Plugin\Quality_Management\Filter;
use acf_field;

class ACF_Custom_Conditional_Logic extends acf_field {
	/**
	 * @param mixed[] $settings
	 */
	public function __construct( $settings = [] ) {
		$this->name = 'custom_conditional_logic';
		$this->label = __( 'Eigene Bedigungen', 'msq' );
		$this->settings = $settings;

    	parent::__construct();
	}

	/**
	 */
	public function input_admin_enqueue_scripts() {
		// vars
		$url = $this->settings[ 'url' ];
		$path = $this->settings[ 'path' ];
		$filename = "{$url}assets/js/acf-custom-conditional-logic.js";
		$version = filemtime( "{$path}assets/js/acf-custom-conditional-logic.js" );

		wp_register_script( 'acf-custom-conditional-logic', $filename, [ 'acf-input' ], $version );
		wp_enqueue_script( 'acf-custom-conditional-logic' );
	}

	/**
	 * @param array $field
	 * @return array
	 */
	public function load_field( $field ) {
		$field[ 'params' ] = [];
		$field[ 'operators' ] = [
			'equals' => 'ist gleich',
			'contains' => 'enthält',
			'starts_with' => 'beginnt mit',
			'ends_with' => 'endet auf',
			'regex' => 'stimmt mit regulärem Ausdruck überein',
			'not_queals' => 'ist nicht gleich',
			'not_contains' => 'enhält nicht',
			'not_start_with' => 'beginnt nicht mit',
			'not_ends_with' => 'endet nicht mit',
			'not_regex' => 'stimmt nicht überein mit regulärem ausdruck',
			'less_than' => 'ist kleiner als',
			'less_equals' => 'ist kleiner als oder gleich',
			'greater_than' => 'ist größer als',
			'greater_equals' => 'ist größer als oder gleich'
		];
		return $field;
	}

	/**
	 * @param array $field
	 */
	public function render_field( $field ) {
		wp_enqueue_style( 'acf-field-group' );

		// vars
		$groups = $field[ 'value' ] ?: [
			'group_0' => [
				'rule_0' => []
			]
		];
		$prefix = $field[ 'name' ];

		// Erzeuge die Tabelle
		?>
			<div class="rule-groups">
				<?php
					foreach( $groups as $group_id => $rules ) {
						$this->render_group( $field, $prefix . '[' . $group_id . ']', $rules, $group_id );
					}
				?>

				<a href="#" class="button add-custom-conditional-group">Oder</a>
			</div>
		<?php
	}

	/**
	 * @param array $field
	 * @param string $prefix
	 * @param mixed $rule
	 * @param int $group_id
	 */
	private function render_group( $field, $prefix, $rules, $group_id ) {
		$headline = $group_id != 'group_0' ? 'Oder' : '';
		?>
			<div class="rule-group" data-id="<?php echo $group_id; ?>">
				<h4><?php echo $headline; ?></h4>

				<table class="acf-table -clear">
					<tbody>
						<?php foreach( $rules as $rule_id => $rule )
							$this->render_table( $field, $prefix . '[' . $rule_id . ']', $rule, $rule_id );
						?>
					</tbody>
				</table>
			</div>
		<?php
	}

	/**
	 * @param array $field
	 * @param string $prefix
	 * @param mixed $rule
	 * @param int $rule_id
	 */
	private function render_table( $field, $prefix, $rule, $rule_id ) {
		$param_value = !empty( $rule[ 'param' ] ) ? $rule[ 'param' ] : '';
		$operator_value = !empty( $rule[ 'operator' ] ) ? $rule[ 'operator' ] : '';
		$value = !empty( $rule[ 'value' ] ) ? $rule[ 'value' ] : '';
		?>
			<tr data-id="<?php echo $rule_id; ?>">
				<?php
					$this->render_param_field( $field, $prefix, $rule, $param_value );
					$this->render_operator_field( $field, $prefix, $rule, $operator_value );
					$this->render_value_field( $field, $prefix, $rule, $value );
				?>
				<td class="add">
					<a href="#" class="button add-custom-conditional-rule">Und</a>
				</td>
				<td class="remove">
					<a href="#" class="acf-icon -minus remove-custom-conditional-rule"></a>
				</td>
			</tr>
		<?php
	}

	/**
	 * @param array $field
	 * @param string $prefix
	 * @param array $rule
	 * @param string $value
	 */
	public function render_param_field( $field, $prefix, $rule, $value ) {
		?>
			<td class="param">
				<?php
					acf_render_field([
						'type'    => 'select',
						'name'    => 'param',
						'prefix'  => $prefix,
						'value'   => $value,
						'choices' => $field[ 'params' ],
					]);
				?>
			</td>
		<?php
	}

	/**
	 * @param array $field
	 * @param string $prefix
	 * @param array $rule
	 * @param string $value
	 */
	public function render_operator_field( $field, $prefix, $rule, $value ) {
		?>
			<td class="operator">
				<?php
					acf_render_field([
						'type'    => 'select',
						'name'    => 'operator',
						'prefix'  => $prefix,
						'value'   => $value,
						'choices' => $field[ 'operators' ]
					]);
				?>
			</td>
		<?php
	}

	/**
	 * @param array $field
	 * @param string $prefix
	 * @param array $rule
	 * @param string $value
	 */
	public function render_value_field( $field, $prefix, $rule, $value ) {
		?>
			<td class="value">
				<?php
					acf_render_field([
						'type'   => 'text',
						'name'   => 'value',
						'class'  => 'layout-label',
						'prefix' => $prefix,
						'value'  => $value
					]);
				?>
			</td>
		<?php
	}

	/**
	 * @param mixed  $value   The value to preview.
	 * @param string $post_id The post ID for this value.
	 * @param array  $field   The field array.
	 */
	public function format_value( $and_values, $post_id, $field ) {
		$and_group = [];
		foreach( (array)$and_values as $or_values ) {
			$or_group = [];
			foreach( (array)$or_values as $rule ) {
				$fieldname = $rule[ 'param' ];
				$value = $rule[ 'value' ];
				switch( $rule[ 'operator' ] ) {
					case 'equals':
						$or_group[] = new Filter\Equals_Criteria( $value, $fieldname );
						break;
					case 'contains':
						$or_group[] = new Filter\Contains_Criteria( $value, $fieldname );
						break;
					case 'starts_with':
						$or_group[] = new Filter\Starts_With_Criteria( $value, $fieldname );
						break;
					case 'ends_with':
						$or_group[] = new Filter\Ends_With_Criteria( $value, $fieldname );
						break;
					case 'regex':
						$or_group[] = new Filter\Regex_Criteria( $value, $fieldname );
						break;
					case 'not_queals':
						$or_group[] = new Filter\Not_Criteria( new Filter\Equals_Criteria( $value, $fieldname ) );
						break;
					case 'not_contains':
						$or_group[] = new Filter\Not_Criteria( new Filter\Contains_Criteria( $value, $fieldname ) );
						break;
					case 'not_start_with':
						$or_group[] = new Filter\Not_Criteria( new Filter\Starts_With_Criteria( $value, $fieldname ) );
						break;
					case 'not_ends_with':
						$or_group[] = new Filter\Not_Criteria( new Filter\Ends_With_Criteria( $value, $fieldname ) );
						break;
					case 'not_regex':
						$or_group[] = new Filter\Not_Criteria( new Filter\Regex_Criteria( $value, $fieldname ) );
						break;
					case 'less_than':
						$or_group[] = new Filter\Not_Criteria( new Filter\Greater_Equals_Criteria( $value, $fieldname ) );
						break;
					case 'less_equals':
						$or_group[] = new Filter\Not_Criteria( new Filter\Greater_Than_Criteria( $value, $fieldname ) );
						break;
					case 'greater_than':
						$or_group[] = new Filter\Greater_Than_Criteria( $value, $fieldname );
						break;
					case 'greater_equals':
						$or_group[] = new Filter\Greater_Equals_Criteria( $value, $fieldname );
						break;
				}
			}
			$and_group[] = new Filter\And_Criteria( $or_group );
		}
		return new Filter\Or_Criteria( $and_group );
	}
}
