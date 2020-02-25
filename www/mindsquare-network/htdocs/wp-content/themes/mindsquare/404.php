<?php 
	get_header();

	ob_start();
	get_sidebar( 'blog' );
	$sidebarContent = ob_get_clean();
?>
<div id="page-container">
	<div class="container">
		<div class="row text-center">
			<?php if( !empty( $sidebarContent ) ): ?>
				<div class="col-lg-9">
			<?php else: ?>
				<div class="col">
			<?php endif; ?>
					<h1>404</h1>
					<h2><?php _e( 'Leider existiert diese Seite nicht.' ); ?></h2>
					<p>Suchen Sie etwas Bestimmtes?</p>
					<?php get_search_form(); ?>
				</div>
			<?php if( !empty( $sidebarContent ) ): ?>
				<div class="col-lg-3">
					<?php echo $sidebarContent; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
	get_footer();
?>
