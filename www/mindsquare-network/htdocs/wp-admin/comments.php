<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to starkers_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>

<?php if ( post_password_required() ) : ?>
				<p><?php _e( 'This post is password protected. Enter the password to view any comments.', 'starkers' ); ?></p>
<?php
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
			<?php /* STARKERS NOTE: The following h3 id is left intact so that comments can be referenced on the page */ ?>
			<h3 id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'starkers' ),
			number_format_i18n( get_comments_number() ), '' . get_the_title() . '' );
			?></h3>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<nav>
		<?php previous_comments_link( __( '&larr; Older Comments', 'starkers' ) ); ?>
		<?php next_comments_link( __( 'Newer Comments &rarr;', 'starkers' ) ); ?>
	</nav>
<?php endif; // check for comment navigation ?>

				<?php
					wp_list_comments( array( 'style' => 'div', 'callback' => 'starkers_comment', 'end-callback' => 'starkers_comment_close' ) );
				?>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	<nav>
		<?php previous_comments_link( __( '&larr; Older Comments', 'starkers' ) ); ?>
		<?php next_comments_link( __( 'Newer Comments &rarr;', 'starkers' ) ); ?>
	</nav>
<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

	if ( ! comments_open() ) :
?>
	<p><?php _e( '', 'starkers' ); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>
<?php 
$args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'title_reply'       => __( 'Schreiben Sie einen Kommentar' ),
  'title_reply_to'    => __( 'Schreiben Sie einen Kommentar' ),
  'cancel_reply_link' => __( 'abbrechen' ),
  'label_submit'      => __( 'Kommentar absenden' ),

  'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Kommentar*', 'noun' ) .
    '</label><br><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
    '</textarea></p>',

  'must_log_in' => '<p class="must-log-in">' .
    sprintf(
      __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
      wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
    ) . '</p>',

  'logged_in_as' => '<p class="logged-in-as">' .
    sprintf(
    __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
      admin_url( 'profile.php' ),
      $user_identity,
      wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
    ) . '</p>',

  'comment_notes_before' => '<p class="comment-notes">' .
    __( 'Wir freuen uns über Ihr Feedback. Bitte füllen Sie alle mit * gekennzeichneten Felder aus. Ihre Emailadresse wird nicht veröffentlicht.' ) . ( $req ? $required_text : '' ) .
    '</p>',

  'comment_notes_after' => '<p class="form-allowed-tags">' .
    sprintf(''),

  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<p class="comment-form-author">' .
      '<label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
      ( $req ? '<span class="required">*</span>' : '' ) .
      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></p>',

    'email' =>
      '<p class="comment-form-email"><label for="email">' . __( 'Emáil', 'domainreference' ) . '</label> ' .
      ( $req ? '<span class="required">*</span>' : '' ) .
      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></p>',

    'url' =>
      '<p class="comment-form-url"><label for="url">' .
      __( 'Website', 'domainreference' ) . '</label>' .
      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></p>'
	  )
  ),
);
?>
<?php comment_form($args); ?>