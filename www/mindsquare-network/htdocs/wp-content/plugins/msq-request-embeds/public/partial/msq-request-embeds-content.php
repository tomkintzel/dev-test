<?php

$request_name = !empty( $atts['name'] ) ? $atts['name'] : '';
$request_text = !empty($atts['request_text']) ? $atts['request_text'] : '';
$button_text = !empty( $atts['button_text'] ) ? $atts['button_text'] : ' mehr erfahren'; 
$postLink  = get_permalink();

$pardot_id = get_field( 'request_embeds_pardot', 'option' );

$pardot = msq_get_pardot_form( array(
    'form_id' => $pardot_id,
    'height' => '300px',
    'querystring' => http_build_query(array(
        'Post_Title' => $request_name,
        'Post_Link' => $postLink,
        'Anfrage_des_Kunden' => $request_name . "(" . $postLink . ")",
        'eventCategory' => 'Angebot anfragen',
        'eventLabel' => $request_name
    ))
));

wp_enqueue_script( 'bootstrap-modal' );
?>
    

<div class="<?php echo $class; ?>">
    <div class="RequestEmbed-Quote">
        <p class="RequestEmbed-QuoteStart"><i class="fa fa-angle-double-right" aria-hidden="true"></i></p>
        <p class="RequestEmbed-QuoteText"><?php echo $request_text; ?></p>
        <p class="RequestEmbed-QuoteEnd"><i class="fa fa-angle-double-left" aria-hidden="true"></i></p>
    </div>
    <div class="RequestEmbed-div">
        <button type="button" class="RequestEmbed-Button" data-toggle="modal" data-target="#requestModal">
            <?php echo $button_text; ?>
        </button>
    </div>
</div>

<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="requestModalLabel"><?php echo $request_name; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="requestModal-Subline">
            Bitte hinterlassen Sie uns Ihren Namen und die Kontaktdaten. Dann melden wir uns bei Ihnen.
        </p>
        <?php if( !empty( $pardot ) ) : ?>
            <div class="requestModal-Pardot">
                <?php echo $pardot; ?>
            </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>