		<?php
			wp_footer();
		?>
		<div class="Footer">
			<div class="container">
				<?php wp_nav_menu([
					'theme_location' => 'footer_menu',
					'menu_class' => 'Footer-Menu'
				]); ?>
				<div class="Footer-Copyright">
					© copyright <?php echo date("Y"); ?> mindsquare GmbH
				</div>
			</div>
		</div>
	</body>
</html>
