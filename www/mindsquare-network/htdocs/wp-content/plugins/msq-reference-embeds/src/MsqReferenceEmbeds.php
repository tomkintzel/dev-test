<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'MsqHook.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'MsqAction.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'MsqFilter.php';

/**
 *
 * @since 1.0.0
 * @package MsqReferenceEmbeds
 * @author Stefan Wiebe <stefan.wiebe@mindsquare.de>
 */
class MsqReferenceEmbeds
{
    const FRONTEND_STYLE = 'msq-reference-embeds-style';
    const BACKEND_STYLE = 'msq-reference-embeds-admin-style';

    /** @var MsqHook[] $hooks */
    private $hooks;

    /**
     * MsqReferenceEmbeds constructor.
     */
    public function __construct()
    {
        $this->hooks = [];
        $this->addHook(new MsqAction('admin_enqueue_scripts', $this, 'enqueueAdminScripts'));
        $this->addHook(new MsqAction('wp_enqueue_scripts', $this, 'enqueueScripts'));
        $this->addHook(new MsqAction('wp_ajax_get_successes', $this, 'getSuccesses'));
        $this->addHook(new MsqFilter('mce_buttons', $this, 'registerMceButtons'));
        $this->addHook(new MsqFilter('mce_external_plugins', $this, 'registerMcePlugin'));
    }

    public function addHook(MsqHook $hook)
    {
        $this->hooks[] = $hook;
    }

    public function enqueueAdminScripts()
    {
        $path = 'assets/css/tinymce-style.min.css';
        $url = plugins_url($path, MSQ_REFERENCE_EMBEDS_FILE);
        $absolutePath = plugin_dir_path(MSQ_REFERENCE_EMBEDS_FILE) . $path;

        wp_register_style(
            self::BACKEND_STYLE,
            $url,
            [],
            filemtime($absolutePath)
        );
    }

    public function enqueueScripts()
    {
        $path = 'assets/css/style.min.css';
        $url = plugins_url($path, MSQ_REFERENCE_EMBEDS_FILE);
        $absolutePath = plugin_dir_path(MSQ_REFERENCE_EMBEDS_FILE) . $path;

        wp_register_style(
            self::FRONTEND_STYLE,
            $url,
            [],
            filemtime($absolutePath)
        );
    }

    public function activatePlugin()
    {
    }

    public function deactivatePlugin()
    {
    }

    public function registerMcePlugin($plugins)
    {
        $plugins['msqReferenceEmbeds'] = plugins_url('/assets/js/tinymce-plugin.min.js', MSQ_REFERENCE_EMBEDS_FILE);
        // CSS nur einbinden, wenn das MCE-Plugin eingebunden wird
        wp_enqueue_style(self::BACKEND_STYLE);
        wp_enqueue_style('css-select2');
        wp_enqueue_script('js-select2');
        return $plugins;
    }

    public function registerMceButtons($buttons)
    {
        $buttons[] = 'msqEmbedReference';
        return $buttons;
    }

    public function getSuccesses()
    {
        /** @var WP_Post[] $successes */
        $successes = get_posts(array(
                                    'posts_per_page' => -1,
                                    'post_type'      => 'success',
                                    'post_status'    => 'publish'
                                ));

        restore_current_blog();

        if (!empty($successes)) {
            $result = [];

            foreach ($successes as $success) {
                $result[ $success->ID ] = $success->post_title;
            }

            wp_send_json($result);
        }
        wp_die();
    }

    public function addShortcode($atts, $content = null)
    {
        /**
         * @var int    $id
         * @var string $display_setting
         */
        $attributes = shortcode_atts(
            [
                    'ids' => null,
                    'with-text' => true,
            ],
            $atts,
            'referenzen_slider'
        );

        $ids = explode(',', $attributes['ids']);
        $withText = filter_var($attributes['with-text'], FILTER_VALIDATE_BOOLEAN);

        return self::getReferenceEmbed($ids, $withText, $content);
    }

    /**
     * @param array  $ids Die IDs der Referenzen, welche eingebunden werden sollen
     * @param bool   $withText Ob der Kurztext bzw. Inhalt der Referenzen angezeigt werden soll
     * @param string $title Der Titel der Einbettung, falls $withText === true
     *
     * @return false|string
     */
    public static function getReferenceEmbed(array $ids, $withText = false, $title = '')
    {
        static $styleEnqueued = false;

        if (!$styleEnqueued) {
            if (!wp_style_is(self::FRONTEND_STYLE, 'enqueued')) {
                wp_enqueue_style(self::FRONTEND_STYLE);
            }

            $styleEnqueued = true;
        }

        $model = [
            'with-text' => $withText,
        ];

        if ($withText) {
            $model['title'] = $title;
        }

        $sliderEntries = [];

        foreach ($ids as $id) {
            $post = get_post($id);

            $reference = [];

            if (!empty($logo = get_field('logo', $post))) {
            	// Logo-ACF, welches extra für diesen Fall bestimmt sind
                $reference['image'] = $logo['url'];
            } elseif (!empty($logo = get_field('thumbnail', $post))) {
            	// Thumbnail-ACF, falls kein Logo vorhanden
				$reference['image'] = $logo['url'];
			} else {
            	// WordPress-Thumbnail, falls kein Thumbnail-ACF vorhanden
				$reference['image'] = get_the_post_thumbnail_url($post, 'succ_lib');
            }

            if ($withText === true) {
                $reference['title'] = get_the_title($post);

                $kurztext = get_field('kurztext', $post);
                $reference['text'] = empty($kurztext) ? '' : $kurztext;
            }

            $sliderEntries[] = $reference;
        }

        $mobileSettings = MSQ_Slider_Settings_Builder::make()
			->setSlidesToShow($withText ? 1 : 3)
			->build();

        $mobileBreakpoint = new MSQ_Slider_Breakpoint(768, $mobileSettings);


        $settings = MSQ_Slider_Settings_Builder::make()
            ->setSlidesToShow($withText ? 2 : 3)
            ->setPrevArrow(
                '<i class="ReferenceSlider-PrevArrow fa fa-2x fa-angle-left"></i>'
            )
            ->setNextArrow(
                '<i class="ReferenceSlider-NextArrow fa fa-2x fa-angle-right"></i>'
            )
			->setResponsive([$mobileBreakpoint])
            ->build();

        $slider = new MSQ_Slider(
            'referenzen-slider',
            null,
            $sliderEntries,
            [
                'uuid' => true
            ],
            $settings
        );

        $slider->enqueueScripts();
        $model['slider'] = $slider;

        ob_start();
        require plugin_dir_path(MSQ_REFERENCE_EMBEDS_FILE) . 'assets/templates/reference-embed.php';
        $result = ob_get_clean();

        if (empty($result)) {
            $result = '';
        }

        return $result;
    }

    public function run()
    {
        if (!defined('MSQ_REFERENCE_EMBEDS_FILE')) {
            return;
        }

        register_activation_hook(MSQ_REFERENCE_EMBEDS_FILE, [$this, 'activatePlugin']);
        register_deactivation_hook(MSQ_REFERENCE_EMBEDS_FILE, [$this, 'deactivatePlugin']);

        foreach ($this->hooks as $hook) {
            $hook->register();
        }

        add_shortcode('referenzen_slider', array( $this, 'addShortcode' ));
    }
}
