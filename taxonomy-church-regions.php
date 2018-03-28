<?php
/**
 * The template for displaying Churches custom post type regions.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$regionName = $term->name;
	
	//replace commas with two dashes to match the actual name in the database
	if (strpos($regionName,',') !== false) {
		$regionName = str_replace(', ','--',$regionName);
	}
	
	$getHeaderImg = get_field('header_graphic');
	if ($getHeaderImg) {
		$headerImg = $getHeaderImg['url'];
	} else {
		$headerImg = get_template_directory_uri() . '/images/header-generic.jpg';
	}
?>
		<section class="section banner sub">
			<div class="angle"></div>
			<div class="inner">
				<div class="shadow"></div>
				<div class="img"><img src="<?php echo $headerImg; ?>" /></div>
				<div class="txt">
					<span><?php echo $term->name; ?></span>
					<div class="banner-dropdowns">
						<div class="banner-dropdown">
							<div class="selected">By Region<i class="icon"></i></div>
							<ul><?php
								$regions = get_terms(array(
									'taxonomy' => 'church-regions',
									'hide_empty' => false,
									'orderby' => 'id'
								));
								if ( ! is_wp_error( $regions ) ){
									foreach ( $regions as $region ) {
										echo '<li><a href="' . esc_url( get_term_link( $region ) ) . '">' . $region->name . '</a></li>';
									}
								}
							?></ul>
						</div><!--end .banner-dropdown-->
						<?php
							$regionargs = array(
								'post_type' => 'churches',
								'tax_query' => array(
									array(
										'taxonomy' => 'church-regions',
										'field' => 'name',
										'terms' => $regionName
									),
								),
								'orderby' => 'title',
								'order' => 'ASC',
								'posts_per_page' => '-1'
							);
							$regionDDItem = new WP_Query( $regionargs );
							if ($regionDDItem->have_posts()) {
								echo '<div class="banner-dropdown">';
								if ('United States' == $regionName) {
									echo '<div class="selected">By State<i class="icon"></i></div>';
								} else {
									echo '<div class="selected">By Country<i class="icon"></i></div>';
								}
								echo '<ul>';
									while ( $regionDDItem->have_posts() ) {
										$regionDDItem->the_post();
										$regionDDLink = get_the_permalink();
										$regionDDTitle = get_the_title();
										echo '<li><a href="' . $regionDDLink . '">' . $regionDDTitle . '</a></li>';
									}
								echo '</ul>';
								echo '</div><!--end .banner-dropdown-->';
							}
							wp_reset_query();
						?>
					</div><!--end .banner-dropdowns-->
				</div>
			</div><!--end .inner-->
			<?php get_sidebar('social-links'); ?>
		</section><!--end .section-->

		<section class="section generic region-detail">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="content<?php if ('United States' == $term->name) {echo ' churches-region';} ?>">
						<?php
							$contentTitle = get_field('content_title','church-regions_' . $term->term_id);
							$contentDesc = get_field('content_description','church-regions_' . $term->term_id);
							if ($contentTitle) {
								echo '<h5>' . $contentTitle . '</h5>';
							}
							if ($contentDesc) {
								if ('United States' == $term->name) {
									echo '<div class="vid"><div class="vidframe"><iframe width="972" height="547" src="https://www.youtube.com/embed/83MUlW-ndBk" frameborder="0" allowfullscreen></iframe></div></div>';
								}
								echo $contentDesc;
							}
						?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm hide"></div>
		</section><!--end .section-->

		<section class="section church-blue sub">
			<div class="angle-blue">
				<img src="<?php echo get_template_directory_uri(); ?>/images/gci-bg.png">
			</div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1><?php
							$terms = get_terms(array(
								'taxonomy' => 'churches',
								'hide_empty' => false,
								'orderby' => 'title',
								'order' => 'ASC'
							));
							if ('United States' == $term->name) {
								echo 'By State';
							} else {
								echo 'By Country';
							}
						?></h1><hr />
					</header>
					<div class="content">
						<ul<?php if ('United States' == $term->name) {echo ' class="us"';} ?>><?php
							$args = array(
								'post_type' => 'churches',
								'tax_query' => array(
									array(
										'taxonomy' => 'church-regions',
										'field' => 'name',
										'terms' => $regionName
									),
								),
								'orderby' => 'title',
								'order' => 'ASC',
								'posts_per_page' => '-1'
							);
							$churchItem = new WP_Query( $args );
							if ($churchItem->have_posts() ) : while ( $churchItem->have_posts() ) : $churchItem->the_post();
								$churchLink = get_the_permalink();
								$churchTitle = get_the_title();
								echo '<li><a href="' . $churchLink . '">' . $churchTitle . '</a></li>';
							endwhile;
							endif;
							wp_reset_query();
						?></ul>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>			
		</section><!--end .section-->
		<script>
			$(document).ready(function() {
				if ($('.church-blue.sub .content > ul li').length <= 0) {
					$('.section.church-blue.sub').hide();
					$('.section.generic .angle-btm').removeClass('hide');
					$('.section.generic .pad').css('padding-bottom','25px');
				}
			});
		</script>
		
<?php 
	get_sidebar('search');
	get_footer();
?>