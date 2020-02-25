<?php
/**
 * Removes and replaces the built-in taxonomy metabox with <select> or series of
 * <input type="radio" />
 *
 * @link https://github.com/WebDevStudios/Taxonomy_Single_Term/
 */
if( !class_exists( 'MSQ_Taxonomy_Single_Term' ) ) {
	class MSQ_Taxonomy_Single_Term {
		/**
		 * @var array Post types where metabox should be replaced (defaults
		 * to all post_types associated with taxonomy)
		 */
		protected $post_types = array();

		/**
		 * @var string Taxonomy slug
		 */
		protected $slug = '';

		/**
		 * @var object Taxonomy object
		 */
		public $taxonomy = false;

		/**
		 * @var string New metabox title. Defaults to Taxonomy name
		 */
		public $metabox_title = '';

		/**
		 * @var string Metabox priority. (vertical placement) 'high',
		 * 'core', 'default' or 'low'
		 */
		public $priority = 'default';

		/**
		 * @var string Metabox position. (column placement) 'normal',
		 * 'advanced', or 'side'
		 */
		public $context = 'side';

		/**
		 * @var boolean Set to true to hide "None" option & force a term
		 * selection
		 */
		public $force_selection = false;

		/**
		 * @var string What input element to use in the taxonomy meta box
		 * (radio or select)
		 */
		protected $input_element = 'radio';

		/**
		 * @var array Default for the selector
		 */
		protected $default = array();

		/**
		 * Initiates our metabox action
		 * @param string $tax_slug Taxonomy slugs
		 * @param array $post_types post-types to display custom metabox
		 * @param string $type display type radio or select
		 * @param array $default default for the taxonomy
		 */
		public function __construct( $tax_slug, $post_types, $type = 'radio', $default = array() ) {
			$this->slug = $tax_slug;
			$this->post_types = is_array( $post_types ) ? $post_types : array( $post_types );
			$this->input_element = in_array( (string) $type, array( 'radio', 'select' ) ) ? $type : $this->input_element;
			$this->default = $this->process_default( $default );

			add_action( 'add_meta_boxes', array( $this, 'add_input_element' ), 1 );
		}

		/**
		 * Process default value for settings
		 *
		 * @param array $default
		 * @return array
		 */
		protected function process_default( $default = array() ) {
			$default = (array) $default;

			if( empty( $default ) ) {
				$default = array( (int) get_option( 'default_' . $this->slug ) );
			}

			foreach( $default as $index => $default_item ) {
				if( is_numeric( $default_item ) ) {
					continue;
				}
				$term = get_term_by( 'slug', $default_item, $this->slug );
				if( $term === false ) {
					$term = get_term_by( 'name', $default_item, $this->slug );
				}
				$default[ $index ] = ( $term instanceof WP_Term ) ? $term->term_id : false;
			}

			return array_filter( $default );
		}

		/**
		 * Removes and replaces the built-in taxonomy metabox with our own
		 */
		public function add_input_element() {
			if( ! $this->taxonomy() ) {
				return;
			}

			foreach( $this->post_types() as $post_type ) {
				// remove default category type metabox
				remove_meta_box( $this->slug . 'div', $post_type, 'side' );
				// remove default tag type metabox
				remove_meta_box( 'tagsdiv-' . $this->slug, $post_type, 'side' );
				// add our custom meta-box
				add_meta_box( $this->slug . '_input_element', $this->metabox_title(), array( $this, 'input_element' ), $post_type, $this->context, $this->priority );
			}
		}

		/**
		 * Displays our taxonomy input metabox
		 */
		public function input_element() {
			// uses same noncename as default box so no save_post hook needed
			wp_nonce_field( 'taxonomy_'. $this->slug, 'taxonomy_noncename' );

			// get terms associated with this post
			$names = wp_get_post_terms( get_the_ID(), $this->slug );

			// filter the ids out of the terms
			$existing = is_wp_error( $names ) || empty( $names ) ? $this->default : array_shift( $names );
			if( isset( $existing->term_id ) ) {
				$existing = array( $existing->term_id );
			}

			// get all terms in this taxonomy
			$terms = (array)get_terms( $this->slug, 'hide_empty=0' );

			// Check if taxonomy is hierarchical
			$hierarchical = $this->taxonomy()->hierarchical;

			// input name
			$name = $this->slug == 'category' ? 'post_category' : 'tax_input[' . $this->slug . ']';
			$name = esc_attr( $hierarchical ? $name . '[]' : $name );

			?>
				<div id="taxonomy-<?php echo $this->slug; ?>" class="<?php echo $this->slug; ?>div tabs-panel">
					<?php if( $this->input_element == 'radio' ): ?>
						<ul id="<?php echo $this->slug; ?>checklist" data-wp-lists="list:<?php echo $this->slug; ?>" class="categorychecklist form-no-clear">
							<?php if( !$this->force_selection ): ?>
								<li style="display:none;">
									<input id="taxonomy-<?php echo $this->slug; ?>-clear" type="radio" name="<?php echo $name; ?>" value="0" />
								</li>
							<?php endif; ?>
							<?php foreach( $terms as $term ):
								// input value
								$value = esc_attr( $hierarchical ? $term->term_id : $term->slug );

								// input id
								$id = esc_attr( $this->slug . '-' . $term->term_id );

								// input selected
								$checked = checked( in_array( $term->term_id, $existing ), true, false );

								// input label
								$label = esc_html( apply_filters( 'the_category', $term->name ) );

								?>
									<li id="<?php echo $id; ?>">
										<label class="selectit">
											<input value="<?php echo $value; ?>" type="radio" name="<?php echo $name; ?>" id="in-<?php echo $id; ?>" <?php echo $checked; ?> />
											<?php echo $label; ?>
										</label>
									</li>
							<?php endforeach; ?>
						</ul>
						<p style="margin-bottom:0;width:50%;">
							<a class="button" id="taxonomy-<?php echo $this->slug; ?>-trigger-clear" href="#"><?php _e( 'Clear' ); ?></a>
						</p>
						<script type="text/javascript">
							jQuery(document).ready(function($){
								$('#taxonomy-<?php echo $this->slug; ?>-trigger-clear').click(function(){
									$('#taxonomy-<?php echo $this->slug; ?> input:checked').prop( 'checked', false );
									$('#taxonomy-<?php echo $this->slug; ?>-clear').prop( 'checked', true );
									return false;
								});
							});
						</script>
					<?php else: ?>
						<select style="display:block;width:100%;margin-top:12px;" name="<?php echo $name; ?>" id="<?php echo $this->slug; ?>checkliste" class="form-no-clear">
							<?php if( !$this->force_selection ): ?>
								<option value="0"><?php echo esc_html( apply_filters( 'msq_taxonomy_single_term_select_none', __( 'None' ) ) ); ?></option>
							<?php endif; ?>
							<?php foreach( $terms as $term ):
								// input value
								$value = esc_attr( $hierarchical ? $term->term_id : $term->slug );

								// input id
								$id = esc_attr( $this->slug . '-' . $term->term_id );

								// input selected
								$selected = selected( in_array( $term->term_id, $existing ), true, false );

								// input label
								$label = esc_html( apply_filters( 'the_category', $term->name ) );

								?>
								<option value="<?php echo $value; ?>" id="<?php echo $id; ?>" class="class-single-term" <?php echo $selected; ?>><?php echo $label; ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</div>
			<?php
		}

		/**
		 * Gets the taxonomy object from the slug
		 * @return object Taxonomy object
		 */
		public function taxonomy() {
			$this->taxonomy = $this->taxonomy ? $this->taxonomy : get_taxonomy( $this->slug );
			return $this->taxonomy;
		}

		/**
		 * Gets the taxonomy's associated post_types
		 * @return array Taxonomy's associated post_types
		 */
		public function post_types() {
			$this->post_types = !empty( $this->post_types ) ? $this->post_types : $this->taxonomy()->object_type;
			return $this->post_types;
		}

		/**
		 * Gets the metabox title from the taxonomy object's labels (or
		 * uses the passed in title)
		 * @return string Metabox title
		 */
		public function metabox_title() {
			$this->metabox_title = !empty( $this->metabox_title ) ? $this->metabox_title : $this->taxonomy()->labels->name;
			return $this->metabox_title;
		}
	}
}
?>
