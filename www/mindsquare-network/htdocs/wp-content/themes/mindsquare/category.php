<?php
	global $wp_query;

	if( is_front_page() ) {
		$page = get_query_var( 'page' );
		$paged = $page ? $page : 1;
	} else {
		$page = get_query_var( 'paged' );
		$paged = $page ? $page : 1;
	}

	wp_enqueue_style( 'bricklayer' );
	wp_enqueue_script( 'bricklayer' );

	get_header();
?>
<div id="page-container" class="py-3">
    <div class="container">
		<h1><?php the_title(); ?></h1>
		<?php if( have_posts() ): ?>
			<div class="bricklayer">
				<?php while( have_posts() ): the_post(); ?>
					<a class="card" href="<?php the_permalink(); ?>">
						<div class="card-image-wrapper">
							<?php the_post_thumbnail( 'post-thumbnail', [
								'class' => 'card-img-top'
							]); ?>
						</div>
						<div class="card-body">
							<p class="card-title h4 pt-0"><?php the_title(); ?></p>
							<div class="card-text">
								<?php the_excerpt(); ?>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
			<?php if ($wp_query->max_num_pages > 1) : ?>
				<div class="pagination">
					<?php echo paginate_links( array(
						'format' => 'page/%#%/',
						'total' => $wp_query->max_num_pages,
						'current' => $paged
					)); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
<script>
window.addEventListener('DOMContentLoaded', function() {
	var bricklayer = new Bricklayer(document.querySelector('.bricklayer'));
});
</script>
<?php
	wp_reset_query();
	get_footer();
?>
