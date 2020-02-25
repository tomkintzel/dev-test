<?php  

$current_blog = get_current_blog_id();

if( $current_blog == 37 ):
    $angebot = explode( "_", $solution_id );
    switch_to_blog( $angebot[0] );
    $solution_link = get_permalink($angebot[1]);          
    $solution_thumb = get_the_post_thumbnail($angebot[1], [363, 242]);
    $solution_title = get_the_title( $angebot[1] );
    // es konnte kein Excerpt verwendet werden. Da durch ein Filter die Actionbar und Endbuttons(rz10)
    // an den Content angehangen wird.
    $postdata = get_post($angebot[1]);
    $postdata = preg_split( '/<!--more(.*?)?-->/' , $postdata->post_content);

    $solution_text = $postdata[0];
    $quote_text = !empty($atts['quote_text']) ? $atts['quote_text'] : '';
    $button_text = !empty( $atts['button_text'] ) ? $atts['button_text'] : ' mehr erfahren';

    $solution_yoast_title = get_post_meta( $angebot[1], '_yoast_wpseo_title', true );
    $solution_yoast_desc = get_post_meta( $angebot[1], '_yoast_wpseo_metadesc', true );
    restore_current_blog();
else:

    $solution_link = get_permalink($solution_id);          
    $solution_thumb = get_the_post_thumbnail($solution_id, [363, 242]);
    $solution_title = get_the_title( $solution_id);
    // es konnte kein Excerpt verwendet werden. Da durch ein Filter die Actionbar und Endbuttons(rz10)
    // an den Content angehangen wird.
    $postdata = get_post($solution_id);
    $postdata = preg_split( '/<!--more(.*?)?-->/' , $postdata->post_content);

    $solution_text = $postdata[0];
    $quote_text = !empty($atts['quote_text']) ? $atts['quote_text'] : '';
    $button_text = !empty( $atts['button_text'] ) ? $atts['button_text'] : ' informieren';

    $custom_headline = !empty( $atts['headline'] ) ? $atts['headline'] : '';
    $custom_body = !empty( $atts['body'] ) ? $atts['body'] : '';
    $solution_title = get_the_title($solution_id);
    $solution_yoast_title = get_post_meta( $solution_id, '_yoast_wpseo_title', true );
    $solution_yoast_desc = get_post_meta( $solution_id, '_yoast_wpseo_metadesc', true );
endif;

    wp_enqueue_script( 'bootstrap-modal' );

    $pardot_id = get_field('solution_embed_pardot', 'option');

    if(!empty( $pardot_id )){
        $pardot = msq_get_pardot_form( array(
            'form_id' => $pardot_id,
            'height' => '300px',
            'querystring' => http_build_query(array(
                'Post_Title' => $solution_title,
                'Post_Link' => $solution_link,
                'eventCategory' => 'unverbindlich anfragen',
                'eventLabel' => $solution_title,
                'Angeforderte_Angebote' => sprintf( "- Angebotstitel: %s\n- Angebotstext: %s\n- Angefragt auf Seite: %s(%s)\n- Mögliches Standardangebot: %s(%s)", $custom_headline, $custom_body, get_the_title(), get_permalink(), $solution_title, $solution_link )
            ))
        ));
    }
    

if($atts['display_setting'] == 'full'): ?>
    <div class="<?php echo $class; ?>">
        <div class="SolutionEmbed-Info">
            <div class="SolutionEmbed-Image SolutionEmbed-Image_full">
                <?php echo $solution_thumb; ?>
            </div>
            <div class="SolutionEmbed-Text SolutionEmbed-Text_full">
                <a href="<?php echo $solution_link; ?>">
                    <h2><?php echo $solution_title ?></h2>
                </a>
                <p><?php echo substr( $solution_text,0,300 ) ?></p>
            </div>
        </div>
        <a class="SolutionEmbed-Button SolutionEmbed-Button_full" href="<?php echo $solution_link; ?>"><?php echo $button_text; ?></a>
    </div>
<?php elseif( $displaySetting == 'custom' ): ?>
    <div class="<?php echo $class; ?>">
        <div class="SolutionEmbed-Text">
            <a href="<?php echo $solution_link; ?>">
                <h6><?php echo $custom_headline; ?></h6>
            </a>
            <p><?php echo $custom_body; ?></p>
        </div>
        <div class="SolutionEmbed-Link">
        <button type="button" class="SolutionEmbed-Trigger" data-toggle="modal" data-target="#solutionModal">
            <?php echo $button_text; ?>
        </button>
        </div>
    </div>

    <div class="modal fade" id="solutionModal" tabindex="-1" role="dialog" aria-labelledby="solutionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="solutionModalLabel">Informationen anfragen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="SolutionEmbed-Modal-Text">
                        <p>Bitte hinterlassen Sie uns Ihren Namen und die Kontaktdaten. Dann melden wird uns bei Ihnen.</p>
                    </div>
                    <?php if( !empty( $pardot ) ): ?>
                        <div class="SolutionEmbed-Modal-Form">
                            <?php echo $pardot; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php elseif($displaySetting == 'quote'): ?>
    <div class="<?php echo $class; ?>">
        <div class="SolutionEmbed-Quote">
            <p class="SolutionEmbed-QuoteStart"><i class="fa fa-angle-double-right" aria-hidden="true"></i></p>
            <p class="SolutionEmbed-QuoteText"><?php echo substr($quote_text,0,130). "..." ?></p>
            <p class="SolutionEmbed-QuoteEnd"><i class="fa fa-angle-double-left" aria-hidden="true"></i></p>
        </div>
            <a class="SolutionEmbed-Button SolutionEmbed-Button_quote" href="<?php echo $solution_link; ?>"><?php echo $button_text; ?></a>
    </div>
<?php else: ?>
<div class="<?php echo $class; ?>">
        <div class="SolutionEmbed-Text">
            <?php if( !empty( $solution_yoast_title ) && strpos( $solution_yoast_title, '%' ) === false ): ?>
                <a href="<?php echo $solution_link; ?>">
                    <h6><?php echo $solution_yoast_title; ?></h6>
                </a>
            <?php else: ?>
                <a href="<?php echo $solution_link;?>">
                    <h6><?php echo $solution_title; ?></h6>
                </a>
            <?php endif; ?>
            <?php if( !empty( $solution_yoast_desc ) ): ?>
                <p><?php echo $solution_yoast_desc; ?></p>
            <?php else: ?>
                <p><?php echo substr( $solution_text, 0, 130 ) . "..."; ?></p>
            <?php endif; ?>
        </div>
        <div class="SolutionEmbed-Link">
            <a class="SolutionEmbed-Button_small" href="<?php echo $solution_link; ?>"><?php echo $button_text; ?></a>
        </div>
    </div>
<?php endif;