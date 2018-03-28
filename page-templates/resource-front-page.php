<?php
/**
 * Template Name: Resource Front Page Template
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */
include_once(get_template_directory() . "/lib/classes/PostHelper.php");
include_once(get_template_directory() . "/lib/classes/Carousel.php");
include_once(get_template_directory() . "/lib/classes/Media.php");
include_once(get_template_directory() . "/lib/classes/Events.php");
include_once(get_template_directory() . "/lib/classes/Featurebox.php");
include_once(get_template_directory() . "/lib/classes/BackgroundImage.php");

get_header();

$post_id = get_the_ID();
$Events = new Events($post_id);
$Media = new Media($post_id);

$post = get_post($post_id);
$content = apply_filters('the_content', $post->post_content);

// $featured_post_ids = Featurebox::getFeaturedPostIds($post_id);
$featured_post_ids = preg_split('@[\s,]+@', get_field('featured_ids'), NULL, PREG_SPLIT_NO_EMPTY);


if (strtolower(get_field('slider_or_image') == 'slider')) {

    $Splash = new Carousel();
    $images = $Splash->getImages();
    $Splash->setAttributes(array('images'=>$images));
}
else {

    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    $image_url = $image[0];

    $bannerBtnTxt = '';
    $bannerBtnLink = '';
    $bannerBtnWin = '';

    if (get_field('banner_buttons')) {

        while (has_sub_field('banner_buttons')) {
            $bannerBtnTxt = get_sub_field('text');
            $bannerBtnLink = get_sub_field('link');
            $bannerBtnWin = '';
            if (get_sub_field('new_window')) {
                $bannerBtnWin = ' target="_blank"';
            }
        }
    }


    $Splash = new BackgroundImage();
    $Splash->setAttributes(
        array('image_url'=>$image_url,
            'largeTopTxt'=>get_field('large_text_top'),
            'largeBtmTxt'=>get_field('large_text_bottom'),
            'small_text'=>get_field('small_text'),
            'largeTopTxt'=>get_field('large_text_top'),
            'largeTopTxt'=>get_field('large_text_top'),
            'bannerBtnTxt'=>$bannerBtnTxt,
            'bannerBtnLink'=>$bannerBtnLink,
            'bannerBtnWin'=> $bannerBtnWin,
        )
    );
}
?>

		<section class="section banner main">
			<div class="angle"></div>
			<div class="inner">
            <!-- splash screen -->
			<?php $Splash->render(); ?>
			</div><!--end .inner-->
		</section><!--end .section-->

		<section class="section quotes">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="content">
						<div class="swiper-quotes">
							<div class="swiper-wrapper">
							<?php echo $content;?>
							</div><!--end .swiper-wrapper-->
						</div><!--end .swiper-quotes-->
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->
		
        <?php if (!empty($featured_post_ids)) {?>
            <section class="section media-home">
                <div class="angle"></div>
                <div class="inner">
                    <div class="pad">
                        <div class="content">
                            <div class="card-cascade wider">
                            <?php
                                foreach ((array)$featured_post_ids AS $featured_post_id)
                                {
                                    echo "<div class='col-lg-4' {$featured_post_id}>";
                                        $FeatureBox = new Featurebox($featured_post_id);
                                        echo $FeatureBox->render();
                                    echo "</div>";
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }?>

        <!-- // Find a church, search bar -->
		<?php //get_sidebar('search'); ?>
		<section class="section media-home hidden">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Publications / Media</h1><hr />
						<h2>Latest Source Articles &amp; Media</h2>
					</header>
					<div class="content">
						<div class="featured-media">
                        <?php
                            $Media->renderFeaturedMedia();
						?>
						</div>
						<div class="latest-info">
							<div class="latest-info-btns">
								<a class="<?php echo ($video_first ? 'on' : '');?>" href="javascript:;" data-link="videos">Latest Videos</a>
								<a class="<?php echo (!$video_first ? 'on' : '');?>" href="javascript:;" data-link="articles">Latest Articles</a>
							</div><!--end .latest-info-btns-->

							<div id="latest-videos" class="latest-items trans <?php echo ($video_first) ? 'on' : '';?>">
							<?php
                                $Media->renderVideo();
							?>
							</div><!--end .latest-videos-->

							<div id="latest-articles" class="latest-items trans <?php echo (!$video_first) ? 'on' : '';?>" style="display:<?php echo (!$video_first) ? 'block' : 'none';?>">
							<?php
                                $Media->renderArticles();
							?>
							</div><!--end .latest-articles-->

						</div>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

		<section class="section news-events hidden">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header>
						<h1>News &amp; Events</h1><hr />
						<h2>What's Happening</h2>
					</header>
					<div class="content">
					<?php
                        $Events->renderFeaturedEvents();
					?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->

<?php  get_footer(); ?>
<script>
    $(document).ready(function() {
        $('.latest-info-btns a').click(function() {
            var latestLink = $(this).data('link');
            $('.latest-info-btns a').removeClass('on');
            $(this).addClass('on');
            $('.latest-items').removeClass('on').hide();
            $('#latest-' + latestLink).show(1,function() {
                $(this).addClass('on');
            });
        });
    });
</script>
