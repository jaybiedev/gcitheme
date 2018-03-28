<?php
/**
 * The Template for displaying all single posts.
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
					<header>
						<h1><?php the_title(); ?></h1><hr />
					</header>
					<div class="content">
					
					<?php while ( have_posts() ) : the_post(); ?>

						<?php 
							echo '<div class="featured-img">' . get_the_post_thumbnail() . '</div>';
							the_content();
						?>

					<?php endwhile; // end of the loop. ?>

					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>