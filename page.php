<?php
/**
 * The template for displaying all pages.
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
					<?php 
						$genericPageTitle = get_field('generic_page_content_title');
						if ($genericPageTitle) {
							echo '<header>';
								echo '<h1>' . $genericPageTitle . '</h1><hr />';
							echo '</header>' . PHP_EOL;
						} 
					?>
					<div class="content">
					<?php 
						while ( have_posts() ) : the_post();
							get_template_part( 'content', 'page' ); 
						endwhile; // end of the loop. 
					?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>