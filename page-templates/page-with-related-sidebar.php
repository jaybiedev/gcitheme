<?php
/**
 *
 * Template name: Page with Related Sidebar
 * The template for displaying resource document.
 * SavvyTeachniques
 *
 * @package gcitheme
 */
include_once(get_template_directory() . "/lib/classes/PostHelper.php");

get_header();
get_sidebar('subheader');

$PostHelper = new PostHelper();
$videoItems = $PostHelper->getVideos();
$articleItems = $PostHelper->getArticles();
$menuItems = $PostHelper->getMenuItems('Main Menu');


$page_id = get_the_ID();
$page = get_post_meta(($page_id));

$relatedpages = get_pages(
    array(
        'child_of'      => wp_get_post_parent_id($page_id),
        'post_type' => 'page'
    )
);
?>
<!-- start content container -->
<input type="hidden" style="display:none;opacity: 0;"  id="is_readmore" value="<?php echo get_post_meta($post->ID, 'is_readmore', true);?>" />
<section class="section generic resource-text">
    <div class="angle"></div>
    <div class="inner pad-bottom-80">
        <div class="pad-vertical-100">
            <?php
            $genericPageTitle = get_field('generic_page_content_title');
            if ($genericPageTitle) {
                echo '<header>';
                echo '<h1>' . $genericPageTitle . '</h1><hr />';
                echo '</header>' . PHP_EOL;
            }
            ?>
            <div class="content resource-content subs col-md-8">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div class="entry-content readmore-<?php echo getReadmore();?>">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; ?>
                <?php else: ?>
                    <?php get_template_part( 'content', 'none' ); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-4 sticky-sidebar">

                <div class="resource-right-sidebar">
                    <h2>Related Resources</h2>
                    <div class="table-responsive resourceSidebarFeature">
                        <table class="table">
                            <?php if ( count($relatedpages) ) {
                                echo "<tr>";
                                foreach  ( $relatedpages as $page) {
                                    if ($page->ID == $page_id)
                                        continue;

                                    $permalink = get_permalink($page);
                                    ?>
                                    <td align="left">
                                        <a href="<?php echo $permalink;?>"><?php echo $page->post_title;?></a>
                                        <?php if (!empty($page->post_excerpt)) {
                                            echo '<div class="side-item-excerpt">' . $page->post_excerpt . '</div>';
                                        }?>
                                    </td>
                                    </tr>
                                <?php }
                            } ?>
                        </table>
                    </div>
                </div>

                <!-- recent articles -->
                <div class="resource-right-sidebar">
                    <h2>Recent Articles</h2>
                    <div class="table-responsive resourceSidebarFeature">
                        <table class="table">
                            <?php
                            while ( $articleItems->have_posts() ) {
                                echo "<tr>";

                                $articleItems->the_post();
                                $articleLink = get_the_permalink();
                                $articleTitle = pjs_truncate(get_the_title(), 26);
                                    ?>
                                    <td align="left">
                                        <a href="<?php echo $articleLink;?>"><?php echo $articleTitle;?></a>
                                    </td>
                                    </tr>

                            <?php } ?>
                        </table>
                    </div>
                </div>

                <!-- recent articles -->
                <div class="resource-right-sidebar">
                    <h2>Quick Links</h2>
                    <div class="table-responsive resourceSidebarFeature">
                        <table class="table">
                            <?php if ( count($menuItems) ) {
                                echo "<tr>";
                                foreach  ( $menuItems as $item) {
                                    if ($item->title == 'Home')
                                        continue;

                                    ?>
                                    <td align="left">
                                        <a href="<?php echo $item->url;?>"><?php echo $item->title;?></a>
                                    </td>
                                    </tr>

                                <?php }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end content container -->
<?php get_footer(); ?>
