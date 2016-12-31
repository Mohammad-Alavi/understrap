<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package understrap
 */

if ( ! function_exists( 'understrap_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function understrap_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	// I commented this
	// if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
	// 	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>, <time class="updated" datetime="%3$s">' . __( ' Edited %4$s', 'understrap' ) . '</time>';
	// }

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$byline = sprintf(
		esc_html_x( '%s', 'post author', 'understrap' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'understrap' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
	$post_avatar = '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_avatar( get_the_author_meta( 'ID' )) . '</a>';

	echo '<span class="post-avatar">' . $post_avatar . '</span>' . '<span class="byline"> ' . $byline . '</span>' . '<span class="posted-on">' . $posted_on . '</span>';

}
endif;

if ( ! function_exists( 'understrap_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function understrap_entry_footer() {
		edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			// I added this
			esc_html__( 'Edit', 'understrap' ),
			//esc_html__( 'Edit %s', 'understrap' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'understrap' ) );
		if ( $categories_list && understrap_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'understrap' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'understrap' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'understrap' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'understrap' ), __( '1 Comment', 'understrap' ), __( '% Comments', 'understrap' ) );
		echo '</span>';
	}

	if( is_home() ) {
		echo '<div id="post-bottom-seperator"></div>';
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function understrap_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'understrap_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'understrap_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so understrap_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so understrap_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in understrap_categorized_blog.
 */
function understrap_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'understrap_categories' );
}
add_action( 'edit_category', 'understrap_category_transient_flusher' );
add_action( 'save_post',     'understrap_category_transient_flusher' );

/**
 * Display navigation to next/previous post when applicable.
 */
if ( ! function_exists( 'understrap_post_nav' ) ) :

	function understrap_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>

		<div class="row">
			<div class="col-md-12">
				<nav class="navigation post-navigation">
					<h2 class="sr-only"><?php _e( 'Post navigation', 'understrap' ); ?></h2>
					<div class="nav-links">
						<?php

							if ( get_previous_post_link() ) {
								previous_post_link( '<span class="nav-previous float-xs-left">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'understrap' ) );
							}
							if ( get_next_post_link() ) {
								next_post_link( '<span class="nav-next float-xs-right">%link</span>',     _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'understrap' ) );
							}
						?>
					</div><!-- .nav-links -->
				</nav><!-- .navigation -->
			</div>
		</div>
		<?php
	}
endif;

/**
 * Custome comment display
 */

 function my_blog_comment($comment, $args, $depth) { 
    if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-meta">
			<div class="comment-author vcard">
				<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
				<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
					<br />
			<?php endif; ?>

			<div class="comment-metadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
				/* translators: 1: date, 2: time */
				printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a>
			</div>
	</div>

	<div class="comment-content">
		<?php comment_text(); ?>
	</div>

	<div class="edit-link">
		<?php edit_comment_link( __( '(Edit)' ), '  ', '' );?>
	</div>

	<div class="reply btn btn-secondary">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>

	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
	<?php
 }