<?php

    global $msqTheme;
    global $wp_query;

    get_header();
    
    $archive_title   = get_field( 'archive_title', 'option' );
    $archive_content = get_field( 'archive_content', 'option' );
    $archive_bg_image = get_field( 'archive_background_image', 'option' );
    
    $is_knowhow_search = isset( $_GET[ 'knowhow-filter' ] ) && strlen( $_GET[ 'knowhow-filter' ] ) > 0;

    the_post();

	//Knowhow FILTER
    $posts_list = [];
	if( $is_knowhow_search ) {
		$searchfield = $_GET['knowhow-filter'];
		$keywords = explode( " ", $searchfield );
		$result_pages = [];
		foreach($keywords as $keyword){
			$wp_query_knowhowfilter = new WP_Query([
                'post_type' => 'knowhow',
                's' => $keyword,
                'posts_per_page' => -1,
                'no_found_rows' => true
            ]);
			$knowhowfilter_list = $wp_query_knowhowfilter->get_posts();
			if (sizeof($knowhowfilter_list) > 0){
				foreach ($knowhowfilter_list as $post) {
					if ( !in_array( $post, $result_pages ) ){
						$result_pages[] = $post;
					}
				}
			}
		}
		$posts_list = $result_pages;			// ausgewählte Knowhow-Seiten
	}//else $posts_list is empty

    // Archiv-Hintergrundgrafik für die Titel-Section
    if ( isset( $archive_bg_image ) ){
        $bg_style = "
            <style>
                #archive-knowhow-header{
                    background-image: url('$archive_bg_image');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                }
            </style>
        ";
        echo $bg_style;
    }
?>

<section id="archive-knowhow-header" class="py-5 knowhow-overview-title<?=($is_knowhow_search?'--no-overlap':'')?> mind-yellow-background ">
    <div class="container">
        
        <?php
            if( !$is_knowhow_search ){
                echo ( "<h1 class='text-center mb-4'> $archive_title </h1>" );
            }
        ?>

        <div class="row justify-content-center mb-4">
        <?php
            $msqTheme->getTemplatePart(
                'template-parts/knowhow-searchbar'
            );
        ?>
        </div>
        
        <?php
            if ( !$is_knowhow_search ):
        ?>
            <div class="text-center mb-5">
                    <?php echo $archive_content; ?>
            </div>
        <?php
            else:
                $searchfield = $_GET['knowhow-filter'];
                if ( isset($posts_list) && sizeof( $posts_list ) > 0 ){
                    echo( "<h1 class='text-center mb-4'>" );
					_e( "Ihre Suche nach \"$searchfield\" ergab folgende Treffer:", 'themify' );
					echo( "</h1>" );
				}else{
					echo( "<h1 class='text-center mb-4'>" );
					_e( "Ihre Suche nach \"$searchfield\" ergab leider keine Treffer.", 'themify' );
					echo( "</h1></br></br>" );
				}
            endif;
        ?>
    </div>
</section>
<section class="py-5 knowhow-overview-categories<?=($is_knowhow_search?'--no-overlap':'')?> mind-white-background">
    <div class="container">
        <?php
            if (!$is_knowhow_search){
                //fixe button styles, falls gewünscht, mit ACF implementieren -> get_sub_field( 'color' ) get_sub_field( 'buttontext' )
                $card_deck_content = msq_add_layout(
                    'card-deck-knowhow-categories',
                    array(
                        'buttoncolor'	=> 'primary',
                        'buttontext'	=> 'ansehen',
                        'background'	=> 'mind-gray-background',
                        'class'		=> 'justify-content-center'
                    ),
                    true
                );
                echo ($card_deck_content);
            }else{
                if ( isset($posts_list) && sizeof( $posts_list ) > 0 ){
                    $msqTheme->getTemplatePart(
                        'template-parts/alphabetical-list',
                        'knowhow',
                        [
                            'posts' => $posts_list,
                        ]
                    );
                }
            }
        ?>
</section>
<section class="py-5 mind-gray-background">
    <div class="container <?=get_field( 'kh_padding-y', 'option' )?>--children">
        <?php 
            //Page Builder 
            $option = 'option';
            // Bei den Knowhows wird kh_ vorangestellt, da Options in der DB keiner POST-ID zugeordnet sind.
            $prepend = 'kh_';
            require( dirname( __FILE__ ) . '/inc/templates/page_builder_v2_layouts.php' ); 

            $pagebuilder_content  = '';  //content vor dem content
            $pagebuilder_content .= !empty( $content ) ? $content : '';

            echo ( $pagebuilder_content );
        ?>
    </div>
</section>
<?php
    get_footer();
?>
