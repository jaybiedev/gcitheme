<?php
/**
 * Template Name: Our Churches Landing Page
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	
	//get categories and show the first one as title
	$catName = get_terms('media-categories');
	if ( $catName && !is_wp_error( $catName ) ) {
		$catArray = array();
		foreach ( $catName as $cat ) {
			$catArray[] = $cat->name;
		}
		$catPageName = $catArray[0];
	}
	
	//get the page ID for the category being used
	$catPageID = get_page_by_title($catPageName);
	
	$getHeaderImg = get_field('header_graphic',$catPageID->ID);
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
					<span><?php
						$titleOverwrite = get_field('header_title_overwrite');
						if ($titleOverwrite) {
							echo $titleOverwrite;
						} else {
							echo get_the_title();
						}
					?></span>
					<div class="banner-dropdowns">
						<div class="banner-dropdown">
							<div class="selected">By Region<i class="icon"></i></div>
							<ul><?php
								$terms = get_terms(array(
									'taxonomy' => 'church-regions',
									'hide_empty' => false,
									'orderby' => 'id'
								));
								if ( ! is_wp_error( $terms ) ){
									foreach ( $terms as $term ) {
										echo '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a></li>';
									}
								}
							?></ul>
						</div><!--end .banner-dropdown-->
					</div><!--end .banner-dropdowns-->
				</div>
			</div><!--end .inner-->
			<?php get_sidebar('social-links'); ?>
		</section><!--end .section-->

		<section class="section generic wide">
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
						
						$fcTitle = get_field('fc_title');
						$fcSubTitle = get_field('fc_sub_title');
						$fcContent = get_field('fc_content');
						$fcType = get_field('fc_media_type');
						$fcImg = get_field('fc_image');
						$fcVid = get_field('fc_video_link');
						
						function addVideo() {
							global $fcVid;
							$vidParse = parse_url($fcVid);
							$vidHost = $vidParse['host'];
							$vidID = $vidParse['path'];
							if ($vidHost == 'youtube.com' || $vidHost == 'www.youtube.com' || $vidHost == 'youtu.be' || $vidHost == 'www.youtu.be') {
								if ($vidID == '/watch') {
									$vidID = $vidParse['query'];
									$vidID = str_replace('v=','/',$vidID);
								}
								$embedVid = '<div class="vid-content"><div class="vidframe"><iframe width="622" height="350" src="https://www.youtube.com/embed' . $vidID . '" frameborder="0" allowfullscreen></iframe></div></div>';
							} else if ($vidHost == 'vimeo.com' || $vidHost == 'www.vimeo.com') {
								$embedVid = '<div class="vid-content"><div class="vidframe"><iframe src="https://player.vimeo.com/video' . $vidID . '?title=0&byline=0&portrait=0" width="622" height="350" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></div>';
							} else {
								$embedVid = '<div class="vid-content"><div class="vidframe"><iframe src="' . $fcVid . '" width="622" height="350" frameborder="0" allowfullscreen></iframe></div></div>';
							}
							echo $embedVid;
						}
						
					?>
						<div class="two-col">
							<?php
								if ($fcType != 'none') {
									echo '<div class="left">';
										if ($fcType == 'image') {
											echo '<img src="' . $fcImg['url'] . '" />';
										} else {
											if ($fcVid) {
												echo '<div class="dt-vid">';
												addVideo();
												echo '</div>';
											}
										}
									echo '</div><!--end .left-->';
									echo '<div class="right">';
								} else {
									echo '<div class="full">';
								}
							?>
							<?php
								if ($fcTitle || $fcSubTitle) {
									echo '<header class="la">';
									if ($fcTitle) {
										echo '<h1>' . $fcTitle . '</h1><hr />';
									}
									if ($fcSubTitle) {
										echo '<h2>' . $fcSubTitle . '</h2>';
									}
									echo '</header>';
								}
								if ($fcType == 'image') {
									echo '<div class="mobile-item"><img src="' . $fcImg['url'] . '" /></div>';
								} else {
									if ($fcVid) {
										echo '<div class="mobile-item"><div class="mobile-vid"></div></div>';
									}
								}
								echo $fcContent;
							?>
							</div><!--end .full or .right-->
						</div><!--end .two-col-->
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->
		<?php if ($fcVid) { ?>
		<script>
			var vidContent = $('.two-col .vid-content').html();
			var mobileVidShowing = false;
			function checkVid() {
				if ($(window).width() < 767) {
					if (!mobileVidShowing) {
						$('.two-col .left, .two-col .left .dt-vid').hide();
						$('.two-col .right .mobile-item').show();
						$('.two-col .right .mobile-vid').prepend(vidContent);
						$('.two-col .left .vidframe').remove();
						mobileVidShowing = true;
					}
				} else {
					if (mobileVidShowing) {
						$('.two-col .right .mobile-item').hide();
						$('.two-col .left, .two-col .left .dt-vid').show();
						$('.two-col .left .dt-vid').show();
						$('.two-col .left .dt-vid').prepend(vidContent);
						$('.two-col .right .mobile-item .vidframe').remove();
						mobileVidShowing = false;
					}
				}
			}
			$(window).resize(function() {
				checkVid();
			});
			$(window).load(function() {
				checkVid();
			});
		</script>
		<?php } ?>
		
		<section class="section church-blue">
			<div class="angle-blue">
				<img src="<?php echo get_template_directory_uri(); ?>/images/gci-bg.png">
			</div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>By Region</h1><hr />
					</header>
					<div class="content">
						<ul><?php
							$terms = get_terms(array(
								'taxonomy' => 'church-regions',
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
			
		</section><!--end .section-->
		
<?php
	get_sidebar('search');
	get_footer();
?>