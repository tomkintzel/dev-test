<div class="wrap">
	<h1>Autorenbox Einstellungen</h1>

	<form method="post" action="options.php">
	    <?php settings_fields( 'ask-the-author-settings-group' ); ?>
	    <?php do_settings_sections( 'ask-the-author-settings-group' ); ?>
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Pardot-Link</th>
	        <td><input type="text" name="pardot-link" value="<?php echo esc_attr( get_option('pardot-link') ); ?>" /></td>
	        </tr>
	    </table>
	    <?php submit_button(); ?>

	</form>
</div>