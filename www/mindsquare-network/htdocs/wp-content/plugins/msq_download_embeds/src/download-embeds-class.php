<?php

class MSQ_Download_Embeds {
	const FULL = 'full';
	const LEFT = 'left';
	const RIGHT = 'right';
	const SMALL = 'small';
	static $DEFAULT_CLASSES = ['vertical-direction', 'Form-whiteText'];

	private static $instance;

	private function __construct() {
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'admin_enqueue_scripts',
		            array( $this, 'enqueue_scripts' ) );
	}

	public static function getInstance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new MSQ_Download_Embeds();
		}

		return self::$instance;
	}

	/** @param WP_Post|int $download */
	public static function get_download_info( $download ) {
		$downloadInfo = [];

		$currentBlogId = get_current_blog_id();
		if( $download != 'video'):
			switch_to_blog( 37 );
			$download = get_post( $download );

			if ( empty( $download ) ) {
				restore_current_blog();
				return null;
			}

			$downloadInfo['title']       = get_the_title( $download );
			$downloadInfo['description'] = get_field( 'download_kurzbeschreibung',
													$download );

			$downloadInfo['img']        = get_field( 'download_image',
													$download );
			$downloadInfo['img']['url'] = wp_get_attachment_image_src( $downloadInfo['img']['ID'],
																	'downloads' )[0];

			$downloadType         = get_field( 'download_type',
											$download );
			$downloadInfo['type'] = $downloadType;

			$downloadInfo['formId'] = get_field( 'download_form_pardot',
												$download );

			if( get_field( 'download_file_typ', $download ) ) {
				$file = get_field( 'download_file_url', $download );
				$downloadInfo[ 'file' ] = [
					'url' => $file,
					'title' => $downloadInfo[ 'title' ]
				];
			} else {
				$downloadInfo[ 'file' ] = get_field( 'download_file', $download );
			}

			restore_current_blog();
			switch_to_blog( $currentBlogId );
			$downloadInfo['href'] = get_permalink( $download );

			restore_current_blog();
		endif;
		return $downloadInfo;
	}

	/**
	 * @param WP_Post|int $download
	 * @param string      $displaySetting
	 */
	public static function get_embed(
		$download, $displaySetting = self::FULL, $additionalModifiers = [],
		$additionalClasses = [], $buttonClasses = []
	) {
		global $post;

		$downloadInfo = self::get_download_info( $download );
		if ( empty( $downloadInfo ) &&  $download != 'anhang' && $download != 'video' ) {
			return null;
		}

		if ( $download == 'anhang' ) {

			$title = get_field('title_attachment', $post->ID);
			$type = 'Anhang';
			$description = get_field('description_attachment', $post->ID);
			$attachment = get_field('attachment', $post->ID);
			$postTitle = get_the_title();
			$postLink = get_permalink();
		}elseif($download == 'video'){
			$title = get_field('title_video', $post->ID);
			$type = 'Video';
			$description = get_field('description_video', $post->ID);
			$url = get_field('url_video', $post->ID);
			$postTitle = get_the_title();
			$postLink = get_permalink();
		}else {
			/**
			 * @var string $title
			 * @var string $description
			 * @var array  $img
			 * @var string $type
			 * @var string $href
			 * @var int    $formId
			 * @var array  $file
			 */
			extract( $downloadInfo, EXTR_PREFIX_SAME, 'di' );
		}

		if ( $displaySetting === self::FULL && class_exists( 'Pardot_Plugin' ) || ( $download == 'anhang' && class_exists( 'Pardot_Plugin' ) || ($download == 'video' && class_exists('Pardot_Plugin') ) ) ) {
			if( $download === 'anhang' ) {
				$formId = get_field( 'anhang_herunterladen', 'option' );
				if( !empty( $formId ) ) {
					if( !empty( $attachment ) ) {
						$form = msq_get_pardot_form( array(
							'form_id' => $formId,
							'height' => '300px',
							'querystring' => http_build_query(array(
								'Post_Title' => $postTitle,
								'Post_Link' => $postLink,
								'Download_Link_Text' => $title,
								'Download_Link' => $attachment[ 'url' ],
								'Heruntergeladene_Anhaenge' => $attachment[ 'url' ],
								'eventCategory' => 'Download Anhang',
								'eventLabel' => $title
							))
						));
					}
				}else{
					return null;
				}
			}elseif($download === 'video'){
				$formId = get_field( 'video_herunterladen', 'option' );
				if( !empty( $formId ) ) {
					if( !empty( $url ) ) {
						$form = msq_get_pardot_form( array(
							'form_id' => $formId,
							'height' => '300px',
							'querystring' => http_build_query(array(
								'Post_Title' => $postTitle,
								'Post_Link' => $postLink,
								'Download_Link_Text' => $title,
								'Download_Link' => $url,
								'Heruntergeladene_Anhaenge' => $url,
								'eventCategory' => 'Download Video',
								'eventLabel' => $title
							))
						));
					}
				}else{
					return null;
				}
			}
			
			else{
				$currentBlogId = get_current_blog_id();

				// Erstelle ein QueryString
				$queryString = array();
				parse_str( $_SERVER[ 'QUERY_STRING' ], $queryString );

				// Lade die Dankesseite
				if( $currentBlogId === 37 ) {
					$downloadsUnternehmensCategorie = has_term( '', 'unternehmens-kategorie' );
					if( $downloadsUnternehmensCategorie ) {
						$dankesseite = get_field( 'completion_page_company_downloads', 'option' );
					} else {
						$dankesseite = get_field( 'completion_page_career_downloads', 'option' );
					}
				} else {
					$dankesseite = get_field( 'downloads_completion_page', 'option' );
				}

				$queryString[ 'Post_Link' ] = $href;
				$queryString[ 'Post_Title' ] = $title;
				if( !empty( $file ) ) {
					$queryString[ 'Download_Link_Text' ] = !empty( $file[ 'title' ] ) ? $file[ 'title' ] : $file[ 'filename' ];
					$queryString[ 'Download_Link' ] = $file[ 'url' ];
				}
				$queryString[ 'redirect' ] = get_permalink( $dankesseite ) . '?download-id=' . ( is_numeric( $download ) ? $download : $download->ID );
				$queryString[ 'classes' ] = self::$DEFAULT_CLASSES;
				$queryString[ 'eventCategory' ] = 'Download in Seite eingebettet';
				$queryString[ 'eventLabel' ] = $title;


				// Lade das Pardot-Formular für normale Downloads
				$form = Msq_Pardot_Adapter::getForm(
					[
						'form_id' => $formId,
						'action'  => $href
					],
					$queryString,
					'download'
				);
			}

			
		}

		switch ( $type ) {
			case 'E-Book':
				$typeDescriptor = __( 'Kostenloses E-Book' ) . ':';
				$additionalModifiers[] = 'e-book';
				break;
			case 'Infografik':
				$typeDescriptor = __( 'Kostenlose Infografik' ) . ':';
				$additionalModifiers[] = 'infografik';
				break;
			case 'Whitepaper':
				$typeDescriptor = __( 'Kostenloses Whitepaper' ) . ':';
				$additionalModifiers[] = 'whitepaper';
				break;
			case 'Anhang':
				$additionalModifiers[] = 'anhang';
				break;
			case 'Video':
				$additionalModifiers[] = 'video';
				break;
			default:
				$typeDescriptor = __( 'Kostenlose(s/r) ' . $type ) . ':';
		}


		$baseClass = 'download-embed';
		$class     = $baseClass;

		switch ( $displaySetting ) {
			case self::LEFT:
			case self::RIGHT:
				$class .= sprintf( ' %s--%s', $baseClass, $displaySetting );
			case self::SMALL:
				$class .= sprintf( ' %s--%s', $baseClass, self::SMALL );
				break;
		}

		if ( !empty ( $additionalModifiers ) ) {
			foreach ( $additionalModifiers as $additionalModifier ) {
				$class .= sprintf( ' %s--%s', $baseClass, $additionalModifier );
			}
		}

		if ( !empty ( $additionalClasses ) ) {
			$class .= ' ' . implode( ' ', $additionalClasses );
		}

		$buttonClassString = '';

		$buttonClasses = apply_filters( 'msq_plugin_download-embeds_button-classes', $buttonClasses );
		if ( !empty ( $buttonClasses ) ) {
			$buttonClassString .= '  ' . implode( ' ', $buttonClasses );
		}

		ob_start();
		?>
		<?php if( !empty($title) ): ?>
			<?php if ( $displaySetting === self::FULL ): ?>
				<div class="<?php echo $class ?>">
			<?php else: ?>
				<a class="<?php echo $class ?>" href="<?php echo $href; ?>" target="_blank">
			<?php endif; ?>
				<?php if ( $displaySetting === self::FULL ): ?>
					<div class="download-embed__head">
						<div class="download-embed__title"><?php echo $title; ?></div>
						<p class="download-embed__text"><?php echo $description; ?></p>
					</div>
				<?php endif; ?>
				<div class="download-embed__body">
					<?php if ( $displaySetting !== self::FULL ): ?>
						<div class="download-embed__title"><?php echo $typeDescriptor; ?></div>
					<?php endif; ?>
					<?php if ( $download != 'anhang' && $download != 'video' ) : ?>
						<div class="download-embed__image">
							<img src="<?php echo $img['url']; ?>">
						</div>
					<?php endif; ?>
					<?php if ( $displaySetting === self::FULL ): ?>
						<div class="download-embed__form"><?php echo $form; ?></div>
					<?php endif; ?>
				</div>
				<?php if ( $displaySetting !== self::FULL ): ?>
					<div class="download-embed__footer">
						<span class="download-embed__button<?php echo $buttonClassString; ?>">
							Download
						</span>
					</div>
				<?php endif; ?>
			<?php if ( $displaySetting === self::FULL ): ?>
				</div>
			<?php else: ?>
				</a>
			<?php endif; ?>
	<?php endif; ?>
		<?php

		$result = ob_get_clean();

		return $result;
	}

	public function get_downloads() {
		switch_to_blog( 37 );

		/** @var WP_Post[] $downloads */
		$downloads = get_posts( array(
			                        'posts_per_page' => -1,
			                        'post_type'      => 'downloads',
			                        'post_status'    => 'publish'
		                        ) );

		restore_current_blog();

		if ( !empty( $downloads ) ) {
			$result = [];

			foreach ( $downloads as $download ) {
				$result[ $download->ID ] = $download->post_title;
			}

			wp_send_json( $result );
		}
		wp_die();
	}

	function init() {
		add_filter( 'mce_external_plugins', array( $this, 'register_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'register_button' ) );

		add_shortcode( 'download', array( $this, 'add_shortcode' ) );

		add_action( 'wp_ajax_get_downloads', array( $this, 'get_downloads' ) );
	}

	function enqueue_scripts() {
		if ( wp_script_is( 'css-select2', 'registered' ) ) {
			wp_enqueue_style( 'css-select2' );
			wp_enqueue_script( 'js-select2' );
		} else {
			wp_enqueue_style( 'css-select2',
			                  plugins_url( '../inc/select2.min.css',
			                               __FILE__ ) );
			wp_enqueue_script( 'js-select2',
			                   plugins_url( '../inc/select2.full.min.js',
			                                __FILE__ ),
			                   array( 'css-select2' ) );
		}
		wp_enqueue_style( 'msq_download_embeds',
		                  plugin_dir_url( __FILE__ ) . '../inc/msq_download_embeds.css',
		                  array(),
		                  filemtime( plugin_dir_path( __FILE__ ) . '../inc/msq_download_embeds.css' ) );
	}

	function register_plugin( $plugins ) {
		$plugins['msq_download_embeds'] = plugins_url( 'download-embeds.js',
		                                               DOWNLOAD_EMBED_PLUGIN_FILE );

		return $plugins;
	}

	function register_button( $buttons ) {
		array_push( $buttons, 'msq_embed_download_btn' );

		return $buttons;
	}

	function add_shortcode( $atts, $content = null ) {
		/**
		 * @var int    $id
		 * @var string $display_setting
		 */
		extract( shortcode_atts( array(
			                         'id'              => null,
			                         'display_setting' => 'full'
								 ), $atts, 'download' ) );
		switch_to_blog(37);
		$status = get_post_status( (int)$id );
		restore_current_blog();
		if($status != 'publish' && !is_admin() && $id != 'anhang' && $id != 'video' ) {
			switch_to_blog(37);
			$result = '';
			$subject = 'Ein Download wurde versucht als Draft auszuspielen';
			$draftDownload = get_post((int)$id);
			restore_current_blog();
			$current_post_id = get_the_ID();
			$notice_date = get_post_meta( $current_post_id, 'error_notice_date', true );
			// Nur alle 24 Stunden eine Benachrichtigung senden
			if( empty( $notice_date ) || ( time() - $notice_date ) > 86400 ) {
				update_post_meta( $current_post_id, 'error_notice_date', time() );
				$link = ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on' ? "https" : "http" ) . "://" . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
				if( !empty( $draftDownload ) ) {
					$message = 'Der nicht veröffentlichter Download ' . $draftDownload->post_title . ' wurde versucht auf der Seite ' . $link . ' als Shortcode auszuführen!';
				} else {
					$message = 'Ein nicht veröffentlichter Download wurde versucht auf der Seite ' . $link . ' als Shortcode auszuführen!';
				}
				wp_mail('seer@mindsquare.de',$subject,$message);
			}
		}else{
			$result = self::get_embed( $id, $display_setting );
		}
		if ( empty( $result ) ) {
			$result = '';
		}

		return $result;
	}
}