<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<!--<header>
						<h1><?php _e( 'Not Found', 'twentytwelve' ); ?></h1><hr />
					</header>-->
					<div class="content">
					
						<article id="post-0" class="post error404 no-results not-found">
							<div class="entry-content">
								<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentytwelve' ); ?></p>
								<?php get_search_form(); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-0 -->

					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->

<?php
	//get_sidebar('search');
	get_footer();
?>