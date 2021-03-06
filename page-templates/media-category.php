<?php
/**
 * Template Name: Media Category Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();

	$mainPageTitle = get_the_title();
	$mainPageLink = get_the_permalink();
	
	$catTitleFormat = $mainPageTitle;
	if (strpos($catTitleFormat,'re Included') !== false) {
		$catTitleFormat = 'You&#039;re Included';
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
					<span><?php
						$titleOverwrite = get_field('header_title_overwrite');
						if ($titleOverwrite) {
							echo $titleOverwrite;
						} else {
							echo $mainPageTitle;
						}
					?></span>
					<div class="banner-dropdowns">
						<div class="banner-dropdown">
							<div class="selected">By Category<i class="icon"></i></div>
							<ul><?php
								$mediaCatPages = wp_list_pages("title_li=&child_of=132&echo=0&depth=1&exclude=");
								echo $mediaCatPages;
							?></ul>
						</div><!--end .banner-dropdown-->
					</div><!--end .banner-dropdowns-->
				</div>
			</div><!--end .inner-->
			
			<div class="share-icons">
				<div class="title">Share This</div>
				<div class="addthis_toolbox dt">
					<div class="custom_images">
						<a class="addthis_button_facebook" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-facebook"></i></a>
						<a class="addthis_button_twitter" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-twitter"></i></a>
						<a class="addthis_button_email" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-envelope"></i></a>
					</div>
				</div>
				<a class="mobile-share" href="javascript:;"><i class="fa fa-share-alt"></i></a>
				<div class="addthis_toolbox mobile">
					<div class="custom_images">
						<a class="addthis_button_facebook" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-facebook"></i></a>
						<a class="addthis_button_twitter" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-twitter"></i></a>
						<a class="addthis_button_email" addthis:url="<?php echo $mainPageLink; ?>" addthis:title="<?php echo $mainPageTitle . ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-envelope"></i></a>
					</div>
				</div>
			</div><!--end .share-icons-->
		</section><!--end .section-->

		<section class="section media-recent">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
				<?php
					function checkFileExists($url) {
						$code = FALSE;
						$options['http'] = array(
							'method' => "HEAD",
							'ignore_errors' => 1,
							'max_redirects' => 0,
							'timeout' => 60
						);
						$body = file_get_contents($url, NULL, stream_context_create($options));
						sscanf($http_response_header[0], 'HTTP/%*d.%*d %d', $code);

						return $code;
					}
					
					$args = array(
						'post_type' => 'videos',
						'tax_query' => array(
							array(
								'taxonomy' => 'media-categories',
								'field' => 'name',
								'terms' => $catTitleFormat
							),
						),
						'orderby' => 'post_date',
						'order' => 'DESC',
						'posts_per_page' => 1
					);
					$mediaItem = new WP_Query( $args );
					if ($mediaItem->have_posts() ) : while ( $mediaItem->have_posts() ) : $mediaItem->the_post();
						$mediaLink = get_the_permalink();
						$mediaTitle = get_the_title();
						
						$videoID = get_field('video_id');
						$youtubeID = get_field('youtube_id');
						$mp3Link = get_field('mp3_link');
						$mp4Link = get_field('mp4_link');
						$wmvVodLink = get_field('wmv_vodcast_link');
						$wmvHRLink = get_field('wmv_hi_res_link');
						$isoLink = get_field('iso_dvd_image_link');
						$teaser = get_field('teaser');
						$widescreen = get_field('widescreen');
						
						$isVideo = get_field('video');
						$isAudio = get_field('audio');
					
						if (strpos($videoID, 'SpOL') === 0){
							$catAbrv = 'SpOL';		
						} else if (strpos($videoID, 'YI') === 0) {
							$catAbrv = 'YI';
						} else if (strpos($videoID, 'DIM') === 0) {
							$catAbrv = 'DIM';
						} else if (strpos($videoID, 'WFO') === 0) {
							$catAbrv = 'PastorVid';
						} else {
							$catAbrv = 'MiscVid';
						}
						
						//set file sizes for video download links
						/*********************************************
						** MP4 and HD WMV
						** 854W > 640W > 640 > 480W > 480
						** 
						** Low Res WMV
						** 480W > 480 > 320
						*********************************************/
						
						$vidSize = '-480';
						$vidSize2 = '-640';
						$w = '';
						if ($widescreen) {
							$vidSize = '-480W';
							$vidSize2 = '-854W';
							$w = 'W';
						}
							
						echo '<header><h1>' . $mediaTitle . '</h1><hr /></header>';
						echo '<div class="media-content">';
							echo '<div class="video-embed">';
							if ($isVideo) {
								if ($youtubeID) {
									echo '<div class="vidframe"><iframe width="972" height="547" src="https://www.youtube.com/embed/' . $youtubeID . '?autoplay=1" frameborder="0" allowfullscreen></iframe></div>';
								} else {
									//create the streaming video links to check
									$playVideoFile1 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-480' . $w . '.mp4';
									$playVideoFile2 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-320' . $w . '.mp4';
									$playVideoFile3 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-480' . $w . '.flv';
									$playVideoFile4 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-320' . $w . '.flv';
									$playVideoFile5 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-8-480' . $w . '.flv';
									$playVideoFile6 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-8-320' . $w . '.flv';
									$playVideoFile7 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-854W' . $w . '.mp4';
									$playVideoFile8 = 'https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '-640' . $w . '.mp4';								
									$videoTypesArray = [$playVideoFile1,$playVideoFile2,$playVideoFile3,$playVideoFile4,$playVideoFile5,$playVideoFile6,$playVideoFile7,$playVideoFile8];
									
									$noVid = true;
									foreach ($videoTypesArray as $fileTypeName){
										$fileCode = checkFileExists($fileTypeName);
										if ($fileCode == '200' OR $fileCode == '302') { //check first link
											$videoToPlay = $fileTypeName;
											$noVid = false;
											break;
										}
									}
									
									if ($noVid) {
										echo '<p>Video unavailable.</p>';
									} else  {
										$videoShortcode = '[video src="' . $videoToPlay . '" autoplay=1]';
										echo '<div class="vidframe">';
										echo do_shortcode($videoShortcode);
										echo '</div>';
									}
								}
							} else {
								echo '<p>Video unavailable.</p>';
							}
							echo '</div>';
							if ($isAudio) {
								echo '<div class="audio-embed">';
									$audioShortcode = '[audio src=https://cloud.gci.org/mediafiles/' . $catAbrv . '/' . $videoID . '.mp3 autoplay=1]';
									echo do_shortcode($audioShortcode);
								echo '</div>';
							}
							echo '<div class="links">';
								if ($isVideo) {
									echo '<div class="link watch"><a href="javascript:;">Watch</a></div>';
								}
								
								if ($mp3Link) {
									echo '<div class="link listen"><a href="javascript:;">Listen</a></div>';
								}

								$downloadMP3File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.mp3';
								$downloadMP4File = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.$vidSize.'.mp4';
								$downloadWMVFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.$vidSize.'.wmv';
								$downloadWMVHDFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.$vidsize2.'.wmv';
								$downloadISOFile = 'http://gcitv.net/dl/'.$catAbrv.'/'.$videoID.'.iso';
		
								if ($mp3Link || $mp4Link || $wmvVodLink || $wmvHRLink || $isoLink) {
									echo '<div class="link options">Download Options<div></div>';
									echo '<ul>';
										if ($mp3Link) {
											echo '<li><a href="'.$downloadMP3File.'">MP3</a></li>';
										}
										if ($mp4Link) {
											echo '<li><a href="'.$downloadMP4File.'">MP4 - iPod</a></li>';
										}
										if ($wmvVodLink) {
											echo '<li><a href="'.$downloadWMVFile.'">WMV - Vodcast</a></li>';
										}
										if ($wmvHRLink) {
											echo '<li><a href="'.$downloadWMVHDFile.'">WMV - High Resolution</a></li>';
										}
										if ($isoLink) {
											echo '<li><a href="'.$downloadISOFile.'">ISO - DVD Image</a></li>';
										}
									echo '</ul>';
									echo '</div>';
								}
								while ( have_posts() ) : the_post();
									echo '<div class="link transcript">Program Transcript +</div>';
								endwhile;
								//if ($catTitleFormat == 'Speaking of Life') {
								if (strpos($videoID, 'SpOL') == 0){
									echo '<div class="link"><a href="/about-us/about-dr-joseph-tkach/">About Dr. Joseph Tkach</a></div>';
								}
								
							echo '</div>';
						echo '</div>';
						echo '<div class="content">';
						if ($teaser) {
							echo $teaser;
						} 
						echo '</div>';
						
					endwhile;
					endif;
					wp_reset_query();
				?>
					<div class="btn wide">
						<a href="/online-giving/">Donate</a>
					</div>
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->

		<?php
			$args1 = array(
				'post_type' => 'videos',
				'tax_query' => array(
					array(
						'taxonomy' => 'media-categories',
						'field' => 'name',
						'terms' => $catTitleFormat
					),
				),
				'orderby' => 'post_date',
				'order' => 'DESC',
				'posts_per_page' => 1
			);
			$mediaItem1 = new WP_Query( $args1 );
			if ($mediaItem1->have_posts() ) : while ( $mediaItem1->have_posts() ) : $mediaItem1->the_post();
				while ( have_posts() ) : the_post();
		?>
		<div class="modal transcript">
			<div class="inner">
				<div class="pad">
					<div class="close-btn trans"></div>
					<header><h1>Program Transcript</h1><hr /></header>
					<div class="content"><?php echo $mediaItem->post->post_content; ?></div><!--end .content-->
					<div class="share-icons">
						<div class="title">Share This</div>
						<div class="addthis_toolbox">
							<div class="custom_images">
								<a class="addthis_button_email" addthis:url="<?php echo $mediaItem->post->guid; ?>" addthis:title="<?php echo $mediaItem->post->post_title. ' | ' . get_bloginfo('title'); ?>"><i class="fa fa-envelope"></i></a>
							</div>
						</div>
					</div><!--end .share-icons-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</div><!--end .modal.transcript-->
		<?php
				endwhile;
			endwhile;
			endif;
			wp_reset_query();
		?>

		<script>
			function modalSize() {
				var windowHeight = $(window).height();
				if ($('.modal.transcript .content').length > 0) {
					var modalPadding = (windowHeight*0.8) - $('.modal.transcript header').outerHeight() - $('.modal.transcript .share-icons').outerHeight();
					$('.modal.transcript .content').css('height',modalPadding + 'px');
				}
			}
			$(document).ready(function() {
				var vidIframeSrc = $('.media-recent .video-embed iframe').attr('src');
				var vidIframe = $('.media-recent .video-embed').html();
				var audioEmbed = $('.media-recent .audio-embed').html();
				$('.media-recent .audio-embed').remove();
				
				$('.media-recent .link.watch').click(function() {
					if (!$('.video-embed').is(':visible')) {
						$('.audio-embed').remove();
						$('.media-content').prepend('<div class="video-embed">' + vidIframe + '</div>');
						var autoPlayVid = vidIframeSrc + '?autoplay=1';
						$('.media-content .video-embed iframe').attr('src',autoPlayVid);
						$('html,body').animate({scrollTop:($('.media-recent').position().top - 100)},200);
					}
				});
				$('.media-recent .link.listen').click(function() {
					if (!$('.audio-embed').is(':visible')) {
						$('.video-embed').remove();
						$('.media-content').prepend('<div class="audio-embed">' + audioEmbed + '</div>');
						$('.audio-embed').show();
						$('html,body').animate({scrollTop:($('.media-recent').position().top - 100)},200);
					}
				});
				$('.media-recent .link.options').click(function() {
					if ($(this).children('ul').is(':visible')) {
						$(this).children('ul').slideUp();
					} else {
						$(this).children('ul').slideDown();
					}
				});
				$('.media-recent .link.transcript').click(function() {
					$('.modal-bg').show();
					$('.modal.transcript').show();
					modalSize();
				});
				$(window).resize(function() {
					modalSize();
				});
			});
		</script>
		
		<section class="section events-blue media-videos">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Archive</h1><hr />
					</header>
					<?php
						$vidargs = array(
							'post_type' => 'videos',
							'tax_query' => array(
								array(
									'taxonomy' => 'media-categories',
									'field' => 'name',
									'terms' => $catTitleFormat
								),
							),
							'orderby' => 'post_date',
							'order' => 'DESC',
							'posts_per_page' => 8,
							'paged' => $paged,
							'post_status' => 'publish'
						);
						$vidItem = new WP_Query($vidargs);
					?>
					<script>
						var posts = '<?php echo serialize( $vidItem->query_vars ); ?>',
						current_page = <?php echo $vidItem->query_vars['paged'] + 1; ?>,
						max_page = <?php echo $vidItem->max_num_pages; ?>
					</script>
					<div class="content ajax_posts">
					<?php
						if ($vidItem->have_posts() ) : while ( $vidItem->have_posts() ) : $vidItem->the_post();
							$vidLink = get_the_permalink();
							$vidTitle = pjs_truncate(get_the_title(),32);
							$hasVideo = get_field('video');
							$hasAudio = get_field('audio');
							$vidTeaser = strip_tags(get_field('teaser'));
							$useTeaser = pjs_truncate($vidTeaser,120);
							
							echo '<div class="item">';
								echo '<div class="title"><a href="' . $vidLink . '"><i class="fa fa-television"></i>' . $vidTitle . '</a></div>';
								echo '<div class="excerpt">' . $useTeaser . '</div>';
								echo '<div class="links">';
									if ($hasVideo) {
										echo '<a href="' . $vidLink . '?m=v">Watch</a>';
									}
									if ($hasAudio) {
										echo '<a href="' . $vidLink . '?m=a">Listen</a>';
									}
								echo '</div>';
							echo '</div>';
						endwhile;
						endif;
						wp_reset_query();
					?>
					</div><!--end .content-->
				<?php 
					if ($vidItem->max_num_pages > 1){
						echo '<div class="btn light pjs_loadmore"><a data-type="video" href="javascript:;">More</a></div>';
					}
				?>
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
		
<?php get_footer(); ?>