<?php
$templatefile=get_field('cv_template');
if (strlen($templatefile)>0) {
	require( plugin_dir_path(__FILE__) . "templates/$templatefile.php");
}
?>