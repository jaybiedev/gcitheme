<?php
/**
 * Template for displaying articles.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0
 */

	get_header();
	get_sidebar('subheader');
	
	$articleTerm = '';
	$terms = get_the_terms( $post->ID, 'article-categories' ); 
	foreach($terms as $term) {
		$articleTerm = $term->name;
	}
	if ($articleTerm != ""){
		$articleTerm = $articleTerm . ': ';
	}
?>

		<section class="section generic article-detail">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<div class="content">
					<header>
						<h1><?php echo $articleTerm . get_the_title(); ?></h1><hr />
					</header>
					<?php
						while ( have_posts() ) : the_post();
							the_content();
							
							$author = get_field('article_author');
							if ($author) {
								echo '<p>' . $author . '</p>';
							}
						endwhile;
						
						echo '<div class="article-btm">';
							$tags = get_the_tags();
							if ($tags) {
								echo '<div class="tags">';
									echo '<div class="icon"><i class="fa fa-tag"></i></div>';
									echo '<div class="txt">';
									$t = 0;
									foreach ($tags as $tag) {
										$t++;
										if ($t > 1) {
											echo ', ';
										}
										echo '<a href="/tag/' . $tag->slug . '/">' . $tag->name . '</a>';
									}
									echo '</div>';
								echo '</div><!--end .tags-->';
							}
							//echo '<div class="help">Was This Article Helpful?<i class="fa fa-thumbs-up"></i><i class="fa fa-thumbs-down"></i></div>';
							echo '<div class="help">' . do_shortcode('[was-this-helpful]') . '</div>';
						echo '</div>';
					?>
						<div class="btn">
							<h3>Help us provide more content like this by giving today</h3>
							<a href="/online-giving/">Donate</a>
						</div>
					</div><!--end .content-->
				</div><!--end .pad-->
			</div><!--end .inner-->
			<div class="angle-btm"></div>
		</section><!--end .section-->

		<?php if (get_field('related_articles_info')) { ?>
		<section class="section events-blue related">
			<div class="angle"></div>
			<div class="inner">
				<div class="pad">
					<header class="light">
						<h1>Related Articles</h1><hr />
					</header>
					<div class="content">
					<?php
						while (has_sub_field('related_articles_info')) {
							$relatedArticle = get_sub_field('article');
							$post = $relatedArticle;
							setup_postdata( $post );
							echo '<div class="item">';
								$raTitle = pjs_truncate(get_the_title(),32);
								$raExcerpt = pjs_truncate(get_the_content(),132);
								$raExcerpt = strip_tags($raExcerpt);
								$raDate = get_the_date('M d, Y');
								$raLink = get_the_permalink();
								
								echo '<div class="title"><a href="' . $raLink . '">' . $raTitle . '</a></div>';
								echo '<div class="date">' . $raDate . '</div>';
								echo '<div class="excerpt">' . $raExcerpt . '</div>';
								echo '<div class="btn light la"><a href="' . $raLink . '">Read More</a></div>';
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