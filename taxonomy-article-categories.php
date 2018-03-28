<?php
/**
 * The template for displaying Articles custom post type categories.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	
	//publications landing page ID
	$pubID = 130;
	
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$regionName = $term->name;
	
	//replace commas with two dashes to match the actual name in the database
	if (strpos($regionName,',') !== false) {
		$regionName = str_replace(', ','--',$regionName);
	}
	
	$getHeaderImg = get_field('header_graphic',$pubID);
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
				</div>
			</div><!--end .inner-->
			<?php get_sidebar('social-links'); ?>
		</section><!--end .section-->

		<section class="section generic wide articles">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="content">
						<?php
							$contentTitle = get_field('page_title','article-categories_' . $term->term_id);
							if ($contentTitle) {
								echo '<header class="center"><h1>' . $contentTitle . '</h1><hr /></header>';
							}
							
							$contentDesc = get_field('page_content','article-categories_' . $term->term_id);
							if ($contentDesc) {
								echo '<div class="article-content">' . $contentDesc . '</div>';
							}
						?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
		</section><!--end .section-->
							
		<section class="section church-blue articles">
			<div class="angle-blue">
				<img src="<?php echo get_template_directory_uri(); ?>/images/gci-bg.png">
			</div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Articles</h1><hr />
					</header>
					<div class="content">
						<?php
							wp_reset_query();
							$args = array(
								'post_type' => 'articles',
								'tax_query' => array(
									array(
										'taxonomy' => 'article-categories',
										'field' => 'name',
										'terms' => $term->name
									),
								),
								'orderby' => 'post_date',
								'order' => 'ASC',
								'posts_per_page' => '-1'
							);
							$articleItem = new WP_Query( $args );
							if ($articleItem->have_posts() ) {
								echo '<ul>';
								while ( $articleItem->have_posts() ) : $articleItem->the_post();
									$articleLink = get_the_permalink();
									$articleTitle = get_the_title();
									echo '<li><a href="' . $articleLink . '">' . $articleTitle . '</a></li>';
								endwhile;
								echo '</ul>';
							}
						?>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->

<?php get_footer(); ?>