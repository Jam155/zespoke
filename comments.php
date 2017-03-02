<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentytwelve_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">
	
	<div class="comment-top-bar row">
		<div class="medium-6">
			<p class="comment-count"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></p>
		</div>
		<div class="medium-6">
			<span>Share: </span><?php echo do_shortcode( '[ess_post]' ); ?>
		</div>
	</div>
	
	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>

		<ul class="commentlist">
			<?php wp_list_comments( array( 'style' => 'ul' ) ); ?>
		</ul><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php _e( 'Comment navigation', 'twentytwelve' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentytwelve' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentytwelve' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'twentytwelve' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(array('title_reply' => 'Leave a Comment', 'class_form' => 'comment-form row', 'label_submit' => 'Submit Comment' ) ); ?>
		
	<script>
	jQuery(document).ready(function($){
	
	// Two Column Comment Form
	// Author: Bill Erickson
	// URL: http://www.billerickson.net/code/two-column-comment-form/
	if( $('.comment-form-author').length ) {
		$('.comment-form').prepend( '<div class="one-half comment-form-right medium-6 small-12" />' );
		$('.comment-form').prepend( '<div class="one-half first comment-form-left medium-6 small-12" />' );
		$('.comment-form-author').appendTo( '.comment-form-left' );
		$('.comment-form-email').appendTo( '.comment-form-left' );
		$('.comment-form-url').appendTo( '.comment-form-left' );
		$('.comment-form-comment').appendTo( '.comment-form-right' );
	}
	
	});
	</script>

</div><!-- #comments .comments-area -->