<?php
/**
 * Template Name: About Us Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
?>

		<section class="section generic about">
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
			<div class="angle-btm"></div>
		</section><!--end .section-->

		<section class="section about1">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<?php 
						$s1Title = get_field('s1_title');
						$s1Content = get_field('s1_content');
						if ($s1Title) {
							echo '<header>';
								echo '<h1>' . $s1Title . '</h1><hr />';
							echo '</header>' . PHP_EOL;
						}
						echo '<div class="content">' . $s1Content;
						if (get_field('s1_show_button')) {
							$s1BtnTxt = get_field('s1_button_text');
							$s1BtnLink = get_field('s1_button_link');
							$s1BtnWin = '';
							if (get_field('s1_new_window')) {
								$s1BtnWin = ' target="_blank"';
							}
							echo '<div class="btn"><a href="' . $s1BtnLink . '"' . $s1BtnWin . '>' . $s1BtnTxt . '</a></div>';
						}
						echo '</div><!--end .content-->' . PHP_EOL;
					?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

		<section class="section about2">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<?php 
						$s2Title = get_field('s2_title');
						$s2Content = get_field('s2_content');
						$s2Img = get_field('s2_image');
						echo '<div class="content">';
							echo '<div class="right">';
								echo '<img src="' . $s2Img['url'] . '" />';
							echo '</div><!--end .right-->';
							echo '<div class="left">';
							if ($s2Title) {
								echo '<header>';
									echo '<h1>' . $s2Title . '</h1><hr />';
								echo '</header>' . PHP_EOL;
							}
							echo '<div class="img"><img src="' . $s2Img['url'] . '" /></div>';
							echo $s2Content;
							if (get_field('s1_show_button')) {
								$s2BtnTxt = get_field('s2_button_text');
								$s2BtnLink = get_field('s2_button_link');
								$s2BtnWin = '';
								if (get_field('s2_new_window')) {
									$s2BtnWin = ' target="_blank"';
								}
								echo '<div class="btn"><a href="' . $s2BtnLink . '"' . $s2BtnWin . '>' . $s2BtnTxt . '</a></div>';
							}
							echo '</div><!--end .left-->';
						echo '</div><!--end .content-->' . PHP_EOL;
					?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

		<section class="section about3">
			<div class="angle-blue">
				<img src="<?php echo get_template_directory_uri(); ?>/images/gci-bg.png">
			</div>
			<div class="inner">
				<div class="pad">
					<?php 
						$s3Title = get_field('s3_title');
						$s3Content = get_field('s3_content');
						if ($s3Title) {
							echo '<header class="light">';
								echo '<h1>' . $s3Title . '</h1><hr />';
							echo '</header>' . PHP_EOL;
						}
						echo '<div class="content">' . $s3Content;
						if (get_field('s3_show_button')) {
							$s3BtnTxt = get_field('s3_button_text');
							$s3BtnLink = get_field('s3_button_link');
							$s3BtnWin = '';
							if (get_field('s3_new_window')) {
								$s3BtnWin = ' target="_blank"';
							}
							echo '<div class="btn light"><a href="' . $s3BtnLink . '"' . $s3BtnWin . '>' . $s3BtnTxt . '</a></div>';
						}
						echo '</div><!--end .content-->' . PHP_EOL;
					?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php get_footer(); ?>