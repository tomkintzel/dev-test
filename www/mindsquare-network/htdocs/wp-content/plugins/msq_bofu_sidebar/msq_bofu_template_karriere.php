<?php
/**
 * Diese Datei wird für die Darstellung der BOFU-Sidebar verwendet.
 * @see ./msq_bofu_sidebar.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_rows( 'elements' ) ) : ?>
    <div class="BofuWrapper">
        <div class="BofuBar">
			<?php
			while ( have_rows( 'elements' ) ) :
				the_row();

				$icon_value  = get_sub_field( 'icon' );
				$label_value = get_sub_field( 'label' );

				if ( get_row_layout() === 'link_element' ) {
					$link_value = get_sub_field( 'link' );
				} else {
					$link_value = '#';
				}

				if ( preg_match( '/^\s*fa\s+fa-file-cv\s*$/i',
				                 $icon_value ) === 1 ) {
					$icon = '<img src="/wp-content/plugins/msq_bofu_sidebar/assets/images/bewerbung-25px.png" alt="cv-icon">';
				} else {
					$icon = '<i class="' . $icon_value . '" aria-hidden="true"></i>';
				}

				$href = ! empty( $link_value ) ? ' href="' . $link_value . '"' : '';

				$anchor = '<a %sclass="%s">%s</a>';
				?>
				
                <div class="BofuBar-Element">
					<?php if($label_value == 'Jetzt bewerben'): ?>
						<a<?php echo $href; ?>
								class="BofuBar-Link schnellbewerbung-link"
								title="<?php echo $label_value; ?>"
						>
							<span class="BofuBar-Icon"><?php echo $icon; ?></span>
							<span class="BofuBar-Label"><?php echo $label_value; ?></span>
						</a>
					<?php else:?>
						<a<?php echo $href; ?>
						class="BofuBar-Link"
						title="<?php echo $label_value; ?>"
				>
					<span class="BofuBar-Icon"><?php echo $icon; ?></span>
					<span class="BofuBar-Label"><?php echo $label_value; ?></span>
				</a>
					<?php endif; ?>
					<?php
					if ( get_row_layout() === 'contact_element' ) {
						$contact = get_sub_field( 'contact' );

						$image      = $contact['image'];
						$first_name = $contact['first_name'];
						$last_name  = $contact['last_name'];
						$position   = $contact['position'];
						$email      = $contact['email'];
						$phone      = $contact['phone'];

						echo <<<HTML
<div class="BofuBar-Contact ContactInfo">
    <span>Ansprechpartner</span>
    <div class="ContactInfo-Id">
        <img class="ContactInfo-Image" src="{$image['url']}" alt="{$image['alt']}">
        <div class="ContactInfo-Details">
            <span class="ContactInfo-Name">$first_name $last_name</span>
            <span class="ContactInfo-Position">$position</span>
        </div>
    </div>
    <div class="ContactInfo-Addresses">
        <a class="ContactInfo-Phone" href="tel:$phone">$phone</a>
        <a class="ContactInfo-Email" href="mailto:$email">$email</a>
    </div>
</div>
HTML;
					}
					if ( get_row_layout() === 'contact_element_whatsapp' ) {
						$contact = get_sub_field( 'contact' );

						$image      = $contact['image'];
						$first_name = $contact['first_name'];
						$last_name  = $contact['last_name'];
						$position   = $contact['position'];
						$whatsapp	= $contact['whatsapp_adress'];
						$whatsapp_title = !empty($contact['whatsapp_adress']['title']) ? $contact['whatsapp_adress']['title'] : 'Jetzt bei WhatsApp schreiben!';

						echo <<<HTML
<div class="BofuBar-Contact ContactInfo">
    <span>Ansprechpartner</span>
    <div class="ContactInfo-Id">
        <img class="ContactInfo-Image" src="{$image['url']}" alt="{$image['alt']}">
        <div class="ContactInfo-Details">
            <span class="ContactInfo-Name">$first_name $last_name</span>
            <span class="ContactInfo-Position">$position</span>
        </div>
    </div>
    <div class="ContactInfo-Addresses">
        <a class="ContactInfo-Phone" href="{$whatsapp['url']}">$whatsapp_title</a>
    </div>
</div>
HTML;
					}
					?>
                </div>
			<?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>
