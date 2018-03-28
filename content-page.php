<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

?>
    <input type="hidden" style="display:none;opacity: 0;"  id="is_readmore" value="<?php echo get_post_meta($post->ID, 'is_readmore', true);?>" />
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content readmore-<?php echo getReadmore();?>">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
