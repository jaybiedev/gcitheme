<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage PJS
 * @since PJS 1.0s
 */
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-content/plugins/pjs-gci-service/service/gci-search-service.php';

get_header();
get_sidebar('subheader');

$searchTerm = $_GET['s'];
$searchType = $_GET['searchType'];
$searchRegion = $_GET['searchRegion'];
$showChurchSearchBox = false;
?>
<section class="section generic search">
	<div class="angle"></div>
	<div class="inner">
		<div class="pad">
			<div class="content">
<?php 
				if ($searchType == "") {
?>
					<header class="dark">
						<h1><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1><hr />
					</header>
<?php					
					// regular Wordpress search
					if ( have_posts() ) : ?>
					
						<div class="search-input">
							<?php get_search_form(); ?>
						</div>
						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', get_post_format() ); ?>
						<?php endwhile; ?>

						<?php numeric_pagination(); ?>

					<?php else : ?>

						<article id="post-0" class="post no-results not-found">
							<header class="entry-header">
								<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h1>
							</header>

							<div class="search-input">
								<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentytwelve' ); ?></p>
								<?php get_search_form(); ?>
							</div>
						</article><!-- #post-0 -->

					<?php endif; ?>
<?php				
				}
				else {
					// GCI service search
					$GCI = new PJSGCIService();
					$miles = 50;
					if ($searchType == "church") {
						if ($searchRegion == 'united-states') {
							$searchResults = $GCI->searchChurches($searchTerm, $miles);						
							if ($searchResults->churchlist) {
?>
								<header class="dark">
									<h1><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1><hr />
								</header>
<?php
								foreach ($searchResults->churchlist as $aChurch) {
									$stateOfChurch = $GCI->getFullStateName($aChurch->ST);
									echo '<article id="post-' . $aChurch->ChurchID . '" class="articles type-articles status-publish hentry">';
									echo '<div class="post-result">';
									echo '<header class="entry-header">';
									echo '<h1 class="entry-title">';
									echo '<a href="/churches/'.$stateOfChurch.'/#' . $aChurch->ChurchID . '" rel="bookmark">' . $aChurch->Name . '</a>';
									echo '</h1>';
									echo '</header><!-- .entry-header -->';

									echo '<div class="entry-summary">';
									echo $aChurch->Full_Address;
									echo '</div>';
									echo '</div>';
									echo '</article>';
								}

								// var_dump($properResults->churchlist);
							}
							else {
								// City popup here
								if ($searchResults->citylist) {
	?>
									<header class="dark">
										<h1>Please select a City ...</h1><hr />
									</header>
	<?php
									//var_dump($searchResults->citylist);
									foreach ($searchResults->citylist as $aCity) {
										echo '<article id="post-' . $aChurch->ChurchID . '" class="articles type-articles status-publish hentry">';
										echo '<div class="post-result">';
										echo '<header class="entry-header">';
										echo '<h1 class="entry-title">';
										echo '<a href="/?s='.get_search_query().', '. $aCity->Key .'&searchType=church" rel="bookmark">' . get_search_query() . ', '. $aCity->Key . '</a>';
										echo '</h1>';
										echo '</header><!-- .entry-header -->';
										echo '</div>';
										echo '</article>';
									}
								}
							}
							
						} 
						else {
							// Region is international
							$churchArgs = array(
								'post_type' => 'churches',
								'orderby' => 'title',
								'tax_query' => array(
									array(
										'taxonomy' => 'church-regions',
										'field' => 'slug',
										'terms' => $searchRegion,
										'operator' => 'IN',
									),
								),
								's' => $searchTerm,
								'order' => 'ASC',
								'posts_per_page' => '-1'
							);
							$churches = new WP_Query( $churchArgs );
?>							
							<header class="dark">
								<h1><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1><hr />
							</header>
<?php
							if ( $churches->have_posts() ) : while ( $churches->have_posts() ) : $churches->the_post();
									// $churchTitle = get_the_title();
									// echo '<li><a href="/churches/' . $churchTitle . '">' . $churchTitle . '</a></li>';									
									get_template_part( 'content', get_post_format() );
								endwhile;
							endif;
							wp_reset_query();							
						}
					}
					else {
						$searchResults = $GCI->searchPastors($searchTerm, $miles);												
						if ($searchResults->pastorlist) {
?>
							<header class="dark">
								<h1><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1><hr />
							</header>
<?php
							foreach ($searchResults->pastorlist as $aPastor) {
								$addr_array = explode(',',$aPastor->Location);
								$stateOfChurch = trim($addr_array[1]);

								echo '<article id="post-' . $aPastor->ChurchID . '" class="articles type-articles status-publish hentry">';
								echo '<div class="post-result">';
								echo '<header class="entry-header">';
								echo '<h1 class="entry-title">';
								echo '<a href="/churches/'.$stateOfChurch.'/#' . $aPastor->ChurchID . '" rel="bookmark">' . $aPastor->FULL_NAME . ' - ' . $aPastor->JobDesc . '</a>';
								echo '</h1>';
								echo '</header><!-- .entry-header -->';

								echo '<div class="entry-summary">';
								echo $aPastor->Name . ' - ' . $aPastor->Location;
								echo '</div>';
								echo '</div>';
								echo '</article>';
							}

							// var_dump($properResults->pastorlist);
						} else {
							$showChurchSearchBox = true;
?>
							<header class="dark">
								<h1><?php printf( __( 'Search Results for: %s', 'twentytwelve' ), '<span>' . get_search_query() . '</span>' ); ?></h1><hr />
							</header>
							<article id="post-0" class="post no-results not-found no-pastor">
								<div class="search-input">
									<p><?php _e( 'Sorry, but nothing matched your search criteria. Please search again below with different criteria.', 'twentytwelve' ); ?></p>
								</div>
							</article><!-- #post-0 -->
<?php
						}
					}
					//die();
				}
?>
			</div><!--end .content-->
		</div><!--end .pad-->
	</div><!--end .inner-->
	<div class="angle-btm"></div>
</section><!--end .section-->

<?php
	if ($showChurchSearchBox) {
		get_sidebar('search');
	}
	//get_sidebar('search');
	get_footer();
?>