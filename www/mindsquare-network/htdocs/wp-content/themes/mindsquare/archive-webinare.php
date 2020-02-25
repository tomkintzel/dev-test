<?php
/* 
    Template Name: Template für Webinarseiten
*/
wp_enqueue_style( 'mindsquare-bootstrap' );
wp_enqueue_style( 'seminare' );
wp_enqueue_script( 'slick' );
wp_enqueue_script( 'old-bootstrap' );
// ACF Informationen laden
$webinare_header_section = get_field( 'webinare_header_section', 'options' );
if( !empty( $webinare_header_section ) ) {
    $webinare_header_section = array_filter( $webinare_header_section, function( $webinar ){
        return get_post_status( $webinar ) == 'publish';
    } );
}

$webinare_title = get_field( 'webinare_header_section_title', 'options' );
$webinare_first_text_title = get_field( 'webinare_first_text_title', 'options' );
$webinare_first_text_body = get_field( 'webinare_first_text_body', 'options' );
// Allgemeine Informationen
$webinare_term_list = get_terms( 'unternehmens-kategorie' );
$webinar_list = array();
foreach( $webinare_term_list as $webinarcategorie_id => $webinar_term ) {
    $webinar_list[$webinarcategorie_id] = get_posts( array( 
        'post_type'     => 'webinare',
        'numberposts'   => -1,
        'tax_query'     => array( 
            array(
            'taxonomy'  => 'unternehmens-kategorie',
            'field'     => 'term_id',
            'terms'     => $webinar_term->term_id
         ))
     ) );
}
get_header();
?>

<div class="seminar-page seminar-einstiegsseite" id="page-container">
    <?php if( !empty( $webinare_header_section ) ): ?>
        <section class="header-slider mind-yellow-background">
            <div class="container">
                <div class="row">
                    <div class="slick-wrapper">
                        <h1><?php echo $webinare_title; ?></h1>
                        <div class="slick">
                            <?php foreach( $webinare_header_section as $webinar ): 
                                $title = get_the_title( $webinar );
                                $permalink = get_permalink( $webinar );
                                $thumbnail = get_field( 'webinar_img', $webinar );
                                $excerpt = get_field( 'webinar_text_top', $webinar ); ?>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="img-wrapper">
                                            <a href="<?php echo $permalink; ?>" title="<?php get_the_title( $webinar->post_title ) ?>">
                                                <img src="<?php echo $thumbnail['url']; ?>" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <a href="<?php echo $permalink ?>"><h2><?php echo $title ?></h2></a>
                                        <p><?php echo $excerpt ?></p>
                                        <div class="btn-wrapper">
                                            <a href="<?php echo $permalink ?>" class="btn btn-mind-green btn-xs">Webinar ansehen</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            jQuery(function() {
                jQuery('.header-slider .slick').slick({
                    slidesToShow: 1,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    prevArrow: "<button type=\"button\" class=\"slick-prev\"></button>",
                    nextArrow: "<button type=\"button\" class=\"slick-next\"></button>"
                });
            });
        </script>
    <?php endif;
    if( $webinare_first_text_body ): ?>
    <section class="text-section background-img">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2><?php echo $webinare_first_text_title ?></h2>
                    <div class="text-wrapper">
                        <?php echo $webinare_first_text_body ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif;
    if( count( $webinare_term_list ) > 0 ): ?>
        <section class="seminar-section">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 masonry">
                                <?php foreach( $webinare_term_list as $webinarcategorie_id => $webinar_term ):
                                    $webinar_term_image_url = get_field( 'bild_fur_webinarkategorie', 'unternehmens-kategorie_' . $webinar_term->term_id );
                                    $webinar_term_image_id = attachment_url_to_postid( $webinar_term_image_url);
                                    $webinar_term_image = wp_get_attachment_image( $webinar_term_image_id, 'seminar-thumb' );
                                    if( count( $webinar_list[ $webinarcategorie_id ] ) > 0 ):?>
                                        <div class="masonry-item">
                                            <div class="seminar-box">
                                                <?php if(isset($webinar_term_image) && !empty($webinar_term_image)): ?>
                                                    <div class="img-wrapper">
                                                        <a href="<?php echo get_term_link($webinar_term); ?>">
                                                            <img src="<?php echo $webinar_term_image_url; ?>" alt="">
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="header-wrapper">
                                                    <a href="<?php echo get_term_link( $webinar_term ); ?>">
                                                        <?php echo $webinar_term->name; ?>
                                                    </a>
                                                </div>
                                                <div class="seminar-wrapper">
                                                    <?php foreach( $webinar_list[$webinarcategorie_id] as $webinar ): 
                                                        $title = get_the_title( $webinar );
                                                        $permalink = get_permalink( $webinar );
                                                        ?>
                                                        <div class="icon-box">
                                                            <ul><li><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></li></ul>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                    endif;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>
<?php
    get_footer();
?>
