<?php
/**
 * Subpage header graphic.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	$uri = $_SERVER["REQUEST_URI"];
	$uri_array = explode('/',$uri);
	$uri_first = $uri_array[1];

	// $getHeaderImg = get_field('header_graphic');
    $getHeaderImg = wp_get_attachment_image_src(get_post_thumbnail_id(), 128);
	// if ('events' == get_post_type()) {
	// 	$getHeaderImg = get_field('header_graphic',128);
	// }
	// if ($getHeaderImg) {
	//	$headerImg = $getHeaderImg['url'];
	// } else {
	//	$headerImg = get_template_directory_uri() . '/images/header-generic.jpg';
	// }

    $headerImg = $getHeaderImg[0];
    if (empty($headerImg)) {
        $headerImg = get_template_directory_uri() . '/images/header-generic.jpg';
    }

    ?>
		<section class="section banner sub">
			<div class="angle"></div>
			<div class="inner">
				<div class="shadow"></div>
				<div class="img"><img src="<?php echo $headerImg; ?>" /></div>
				<div class="txt"><span><?php
					$titleOverwrite = get_field('header_title_overwrite');
					if (is_search()) {
						echo 'Search';
					} else if ('events' == get_post_type()) {
						$titleOverwrite = get_field('header_title_overwrite',128);
						if ($titleOverwrite) {
							echo $titleOverwrite;
						} else {
							echo 'Events';
						}
					} else if ($uri_first == 'articles') {
						$articleTerm = '';
						$terms = get_the_terms( $post->ID, 'article-categories' ); 
						foreach($terms as $term) {
							$articleTerm = $term->name;
						}
						if ($articleTerm == ""){
							$articleTerm = "Articles";
						}
						echo $articleTerm;
					} else if (is_404()) {
						echo 'Not Found';
					} else if (is_tag()) {
						echo 'Articles';
					} else if (is_archive() || is_category() || is_author()) {
						echo 'Archive';
					} else if (is_single() || is_home()) {
						echo 'Blog';
					} else {
						if ($titleOverwrite) {
							echo $titleOverwrite;
						} else {
							echo get_the_title();
						}
					}
				?></span>
                    <div class="title-excerpt"><?php echo get_the_excerpt();?></div>
				<?php
					//add article categories dropdown if necessary
					if ($uri_first == 'publications' || $uri_first == 'articles' || $uri_first == 'tag') {
				?>
					<div class="banner-dropdowns">
						<div class="banner-dropdown">
							<div class="selected">By Category<i class="icon"></i></div>
							<ul><?php
								$articleCats = get_terms(array(
									'taxonomy' => 'article-categories',
									'hide_empty' => false,
									'orderby' => 'id'
								));
								if ( ! is_wp_error( $articleCats ) ){
									foreach ( $articleCats as $articleCat ) {
										echo '<li><a href="' . esc_url( get_term_link( $articleCat ) ) . '">' . $articleCat->name . '</a></li>';
									}
								}
							?>
								<li><a href="http://update.gci.org/" target="_blank">GCI Weekly Update</a></li>
								<li><a href="https://equipper.gci.org/" target="_blank">Equipper</a></li>
								<li><a href="http://women.gci.org/" target="_blank">Connections (Women's Ministry)</a></li>
							</ul>
						</div><!--end .banner-dropdown-->
					</div><!--end .banner-dropdowns-->
				<?php } ?>
				</div>
			</div><!--end .inner-->
			<?php get_sidebar('social-links'); ?>
		</section><!--end .section-->