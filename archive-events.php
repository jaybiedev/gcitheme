<?php
/**
 * The template for displaying Events custom post type archive.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
	
	$eID = 128;
	
	$eventImg = get_field('event_image',$eID);
?>

		<section class="section events">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="content">
					<?php if ($eventImg) { ?>
						<div class="left">
							<?php echo '<a href="' . get_field('event_link',$eID) . '">'; ?><img src="<?php echo $eventImg['url']; ?>" alt="<?php echo get_field('event_title',$eID); ?>" /></a>
						</div>
						<div class="right">
					<?php } ?>
							<header>
								<h1><a href="<?php echo get_field('event_link',$eID); ?>"><?php echo get_field('event_title',$eID); ?></a></h1><hr />
								<h2><?php echo get_field('event_date',$eID); ?></h2>
							</header>
							<?php
								echo get_field('event_description',$eID); 
								if (get_field('event_link',$eID)) {
									echo '<div class="btn"><a href="' . get_field('event_link',$eID) . '">Read More</a></div>';
								}
							?>
					<?php if ($eventImg) { ?>
						</div>
					<?php } ?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

		<section class="section events-blue upcoming">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Upcoming Events</h1><hr />
					</header>
					<?php
						$args = array(
							'post_type' => 'events',
							'orderby' => 'meta_value',
							'meta_key' => 'start_date',
							'order' => 'ASC',
							'posts_per_page' => 4,
							'paged' => $paged,
							'post_status' => 'publish',
							'suppress_filters' => true
						);
						$eventItem = new WP_Query( $args );
					?>
					<script>
						var posts = '<?php echo serialize( $eventItem->query_vars ); ?>',
						current_page = <?php echo $eventItem->query_vars['paged'] + 1; ?>,
						max_page = <?php echo $eventItem->max_num_pages; ?>
					</script>
					<div class="content ajax_posts">
					<?php
						if ($eventItem->have_posts() ) : while ( $eventItem->have_posts() ) : $eventItem->the_post();
							$eventLink = get_the_permalink();
							$eventTitle = pjs_truncate(get_the_title(),32);
							$eventStartDate = get_field('start_date');
							$eventEndDate = get_field('end_date');
							$eventExcerpt = pjs_truncate(get_the_excerpt(),132);
							
							echo '<div class="item">';
								echo '<div class="title"><a href="' . $eventLink . '">' . $eventTitle . '</a></div>';
								echo '<div class="date">';
								if ($eventStartDate) {
									$startDate = date('M j, Y',strtotime($eventStartDate));
									echo $startDate;
									if ($eventEndDate) {
										$endDate = date('M j, Y',strtotime($eventEndDate));
										echo ' - ' . $endDate;
									}
								}
								echo '</div>';
								echo '<div class="excerpt">' . $eventExcerpt . '</div>';
								echo '<div class="btn light la"><a href="' . $eventLink . '">Read More</a></div>';
							echo '</div>';
						endwhile;
						endif;
						wp_reset_query();
					?>
					</div><!--end .content-->
				<?php 
					if ($eventItem->max_num_pages > 1){
						echo '<div class="btn light pjs_loadmore"><a data-type="event" href="javascript:;">More</a></div>';
					}
				?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		
<?php get_footer(); ?>