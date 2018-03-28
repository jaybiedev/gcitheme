<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
	global $post_id;
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (get_the_post_thumbnail()) { ?>
		<div class="post-result left">
			<?php echo '<a href="' . get_the_permalink() . '">' . get_the_post_thumbnail($post_id, 'medium', array('alt' => get_the_title(),'title' => get_the_title())) . '</a>'; ?>
		</div>
		<div class="post-result right">
	<?php } else { ?>
		<div class="post-result">
	<?php } ?>
			<header class="entry-header">
				<h1 class="entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
			</header><!-- .entry-header -->

			<div class="entry-summary">
				<?php
					if (is_search()) {
						echo '<a class="searchLink" href="' . get_the_permalink() . '">' . get_the_permalink() . '</a>';
					}
					
					$pageExcerpt = strip_tags(get_field('excerpt'));
					$excerpt = strip_tags(pjs_truncate(get_the_excerpt(), 200));
					$template = get_post_meta( $post->ID, '_wp_page_template', true );
					if ($template == 'page-templates/template-redirect.php' || $template == 'page-templates/template-redirect-https.php') {
						echo '<a href="' . get_the_permalink() . '">' . get_the_permalink() . '</a>';
					} else {
						if ($pageExcerpt) {
							echo $pageExcerpt;
						} else if ($excerpt) {
							echo $excerpt;
						}
					}
				?>
			</div>
		</div>
	</article><!-- #post -->
