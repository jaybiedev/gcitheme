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

		<section class="section event-detail">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header>
						<h1><?php the_title(); ?></h1><hr />
					</header>
					<div class="content">
					
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							$eventStartDate = get_field('start_date');
							$eventEndDate = get_field('end_date');
							$eventTime = get_field('time');
							$locName = get_field('location_name');
							$loc = get_field('location_address');
							$scheduleLink = get_field('schedule_link');
							$registerLink = get_field('register_link');
							
							echo '<div class="event-info">';
								echo '<div class="featured-img">' . get_the_post_thumbnail() . '</div>';
								echo '<div class="event-details">';
								
									if ($eventStartDate) {
										$startDate = date('F j, Y',strtotime($eventStartDate));
										$endDate = date('F j, Y',strtotime($eventEndDate));
										echo '<div class="item"><h4>Date:</h4>';
										echo $startDate;
										if ($endDate) {
											echo ' - ' . $endDate;
										}
										echo '</div>';
									}
									
									if ($eventTime) {
										echo '<div class="item"><h4>Time:</h4>' . $eventTime . '</div>';
									}
									
									if ($locName || $loc) {
										echo '<div class="item"><h4>Location:</h4>';
										if ($locName) {
											echo $locName;
										}
										if ($loc) {
											$locLink = str_replace('<br/>',', ',str_replace('<br>',', ',str_replace('<br />',', ',$loc)));
											echo '<a href="https://www.google.com/maps/place/' . $locLink . '" target="_blank">' . $loc . '</a>';
										}
										echo '</div>';
									}
									
									if ($scheduleLink) {
										echo '<div class="item"><h4>Schedule:</h4>';
											echo '<a href="' . $scheduleLink . '" target="_blank">Click Here</a>';
										echo '</div>';
									}
									
									if ($registerLink) {
										echo '<div class="btn"><a href="' . $registerLink . '">Register</a></div>';
									}
									
								echo '</div>';
							echo '</div>';
							echo '<div class="description">';
								the_content();
							echo '</div>';
						?>

					<?php endwhile; // end of the loop. ?>

					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

		<?php if (get_field('related_event_info')) { ?>
		<section class="section events-blue related">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Related Events</h1><hr />
					</header>
					<div class="content">
					<?php
						while (has_sub_field('related_event_info')) {
							$relatedEvents = get_sub_field('event');
							$post = $relatedEvents;
							setup_postdata( $post );
							echo '<div class="item">';
								$reTitle = pjs_truncate(get_the_title(),32);
								$reExcerpt = strip_tags(get_the_content());
								$reExcerptToUse = pjs_truncate($reExcerpt,132);
								$reStartDate = get_field('start_date');
								$reEndDate = get_field('end_date');
								$reLink = get_the_permalink();
								
								echo '<div class="title"><a href="' . $reLink . '">' . $reTitle . '</a></div>';
								echo '<div class="date">';
								if ($reStartDate) {
									$reSDate = date('M j, Y',strtotime($reStartDate));
									$reEDate = date('M j, Y',strtotime($reEndDate));
									echo $reSDate;
									if ($reEDate) {
										echo ' - ' . $reEDate;
									}
								}
								echo '</div>';
								echo '<div class="excerpt">' . $reExcerptToUse . '</div>';
								echo '<div class="btn light la"><a href="' . $reLink . '">Read More</a></div>';
							echo '</div>';
							wp_reset_postdata();
						}
					?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		<?php } ?>
		
<?php get_footer(); ?>