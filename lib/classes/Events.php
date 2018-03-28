<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');

class Events extends AbstractComponent
{

    public $PostHelper;
    public $post_id;

    function __construct($post_id)
    {
        $this->post_id = $post_id;

        $this->PostHelper = new PostHelper();

    }


    function getContent() {
        //
    }


    function renderFeaturedEvents() {
        return null;

        $eventItems = $PostHelper->getEvents();
        while ( $eventItems->have_posts() ) {
            $eventItems->the_post();
            $eventImg = get_the_post_thumbnail($page->ID, 'event-thumb');
            $eventImgMobile = get_the_post_thumbnail();
            $eventLink = get_the_permalink();
            $eventTitle = pjs_truncate(get_the_title(), 32);
            $eventStartDate = get_field('start_date');
            $eventEndDate = get_field('end_date');
            $eventExcerpt = strip_tags(get_the_content());
            $eventExcerptToUse = pjs_truncate($eventExcerpt, 120);

            if (!$eventImg) {
                $eventImg = '<img src="' . get_template_directory_uri() . '/images/event-ph.jpg" alt="' . $eventTitle . '" />';
            }

            echo '<div class="item">';
            echo '<a class="dt-link" href="' . $eventLink . '">' . $eventImg . '</a>';
            echo '<a class="mobile-link" href="' . $eventLink . '">' . $eventImgMobile . '</a>';
            echo '<div class="info">';
            echo '<div class="title">' . $eventTitle . '</div>';
            echo '<div class="date">';
            if ($eventStartDate) {
                $startDate = date('M j, Y', strtotime($eventStartDate));
                $endDate = date('M j, Y', strtotime($eventEndDate));
                echo $startDate;
                if ($endDate) {
                    echo ' - ' . $endDate;
                }
            }
            echo '</div>';
            echo '<div class="excerpt">' . $eventExcerptToUse . '</div>';
            echo '<div class="btn"><a href="' . $eventLink . '">Read More</a></div>';
            echo '</div>';
            echo '</div><!--end .item-->';
        }

        wp_reset_query();

    }
}