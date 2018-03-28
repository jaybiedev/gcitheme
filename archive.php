<?php
/**
 * The template for displaying Archive pages.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic search">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header>
						<h1><?php
						if ( is_day() ) :
							printf( __( 'Daily Archives: %s', 'twentytwelve' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Monthly Archives: %s', 'twentytwelve' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'twentytwelve' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Yearly Archives: %s', 'twentytwelve' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'twentytwelve' ) ) . '</span>' );
						else :
							_e( 'Archives', 'twentytwelve' );
						endif;
					?></h1><hr />
					</header>
					<div class="content">
					
						<?php if ( have_posts() ) : ?>
							<?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();

								/* Include the post format-specific template for the content. If you want to
								 * this in a child theme then include a file called called content-___.php
								 * (where ___ is the post format) and that will be used instead.
								 */
								get_template_part( 'content', get_post_format() );

							endwhile;

							numeric_pagination();
							?>

						<?php else : ?>
							<?php get_template_part( 'content', 'none' ); ?>
						<?php endif; ?>

					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>