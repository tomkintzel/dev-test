<div id="ask-the-author" class="bg-color">
	
	<div class="consultant">

		<h2><?php echo $author_link; ?></h2>

		<?php echo get_avatar( $email, $size = 121, $default = "", $alt = $alt ); ?>

		<div class='ask_author_text'>

			<p><?php echo get_the_author_meta('description', $post->post_author); ?></p>

		</div>

		<div class='question_article'>

			<p>Sie haben Fragen? <a id='ask_author_link'>Kontaktieren Sie mich!</a></p>

		</div>

	</div>



	<div class='ask_author_form'>

		<?php echo $pardot_form; ?>

	</div>

</div>