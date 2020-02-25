<?php
/**
 * Diese Datei wird für die Darstellung der BOFU-Sidebar verwendet.
 * @see ./msq_bofu_sidebar.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_rows( 'elements' ) ) : ?>
    <div class="bofu-section">
        <div class="bofu-sidebar">
			<?php
			while ( have_rows( 'elements' ) ) :
				the_row();

				$icon  = get_sub_field( 'icon' );
				$link  = get_sub_field( 'link' );
				$label = get_sub_field( 'label' );

				echo <<<HTML
<a class="bofu-item" href="$link">
    <div class="bofu-icon">
        <i class="$icon" aria-hidden="true"></i>
    </div>
    <div class="bofu-body">
        <div class="bofu-btn-wrapper">
            <span class="btn-bofu">$label</span>
        </div>
    </div>
</a>
HTML;
			endwhile; ?>
        </div>
    </div>
<?php endif; ?>
