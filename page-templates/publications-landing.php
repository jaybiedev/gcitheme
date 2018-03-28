<?php
/**
 * Template Name: Publications Landing Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic pubs">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
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
		
		<section class="section events-blue upcoming">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Recent Articles</h1><hr />
					</header>
					<div class="content">
					<?php
						$args = array(
							'post_type' => 'articles',
							'orderby' => 'post_date',
							'order' => 'DESC',
							'posts_per_page' => 4
						);
						$eventItem = new WP_Query( $args );
						if ($eventItem->have_posts() ) : while ( $eventItem->have_posts() ) : $eventItem->the_post();
							$eventLink = get_the_permalink();
							$eventTitle = pjs_truncate(get_the_title(),32);
							$eventDate = get_field('date');
							$eventExcerpt = pjs_truncate(get_the_excerpt(),132);
							
							echo '<div class="item">';
								echo '<div class="title"><a href="' . $eventLink . '">' . $eventTitle . '</a></div>';
								echo '<div class="date">' . $eventDate . '</div>';
								echo '<div class="excerpt">' . $eventExcerpt . '</div>';
								echo '<div class="btn light la"><a href="' . $eventLink . '">Read More</a></div>';
							echo '</div>';
						endwhile;
						endif;
						wp_reset_query();
					?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		
		<section class="section pub-white">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header>
						<h1>Publications by Category</h1><hr />
						<h2>We have more than a thousand articles available on a wide variety of biblical, theological and practical topics.</h2>
					</header>
					<div class="content">
						<ul><?php
							$terms = get_terms(array(
								'taxonomy' => 'article-categories',
								'hide_empty' => false,
								'orderby' => 'id',
								
							));
							if ( ! is_wp_error( $terms ) ){
								foreach ( $terms as $term ) {
									echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a></li>';
								}
							}
						?></ul>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
			<!--<div class="angle-btm-right"></div>-->
		</section><!--end .section-->
		
<?php get_footer(); ?>