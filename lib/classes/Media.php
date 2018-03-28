<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');

class Media extends AbstractComponent  {

    public $PostHelper;
    public $image_url;
    public $title;
    public $excerpt;
    public $description;

    function __construct($post_id) {
        $this->post_id = $post_id;

        $this->PostHelper = new PostHelper();

    }

    function getContent() {
        //
    }


    function renderVideo() {
        return null;

        if ( ! get_post($this->post_id) )
            return '';

        $mediaItems = $this->PostHelper->getVideos();
        while ( $mediaItems->have_posts() ) {
            $mediaItems->the_post();

            $mediaLink = get_the_permalink();
            $mediaTitle = pjs_truncate(get_the_title(), 26);
            $playTime = get_field('play_time');
            $hasVideo = get_field('video');
            $hasAudio = get_field('audio');

            $termName = '';
            $taxonomy = 'media-categories';
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            if ($terms && !is_wp_error($terms)) :
                foreach ($terms as $term) {
                    $termLink = get_term_link($term->slug, $taxonomy);
                    $termName = $term->name;
                }
            endif;
            //replace commas with two dashes to match the actual name in the database
            if (strpos($termName, ',') !== false) {
                $termName = str_replace(', ', '--', $termName);
            }

            echo '<div class="item">';
            echo '<div class="title">';
            echo '<i class="fa fa-television"></i> ' . $mediaTitle . ' <span>- (' . $playTime . ' minutes)</span>';
            echo '</div>';
            echo '<div class="category">' . $termName . '</div>';
            echo '<div class="links">';
            if ($hasVideo) {
                echo '<a href="' . $mediaLink . '">Watch</a>';
            }
            if ($hasAudio) {
                echo '<a href="' . $mediaLink . '">Listen</a>';
            }
            if ($termName != '') {
                echo '<a href="' . $termLink . '">More ' . $termName . '</a>';
            }
            echo '</div>';
            echo '</div><!--end .item-->';

        }

        return $html;
    }

    function renderArticles()
    {
        return null;

        $articleItems = $this->PostHelper->getArticles();

        while ( $articleItems->have_posts() ) {

            $articleItems->the_post();
            $articleLink = get_the_permalink();
            $articleTitle = pjs_truncate(get_the_title(), 26);

            $articleCatName = '';
            $taxonomy = 'article-categories';
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            if ($terms && !is_wp_error($terms)) :
                foreach ($terms as $term) {
                    $articleCatName = $term->name;
                    $articleCatLink = get_term_link($term->slug, $taxonomy);
                }
            endif;
            //replace commas with two dashes to match the actual name in the database
            if (strpos($articleCatName, ',') !== false) {
                $articleCatName = str_replace(', ', '--', $articleCatName);
            }

            echo '<div class="item">';
            echo '<div class="title">';
            echo '<i class="fa fa-newspaper-o"></i> ' . $articleTitle;
            echo '</div>';
            echo '<div class="category">' . $articleCatName . '</div>';
            echo '<div class="links">';
            echo '<a href="' . $articleLink . '">Read More</a>';
            if ($articleCatName != '') {
                echo '<a href="' . $articleCatLink . '">More ' . $articleCatName . '</a>';
            }
            echo '</div>';
            echo '</div><!--end .item-->';

        }
    }


    function renderFeaturedMedia()
    {
        return null;

        $articleItems = $this->PostHelper->getArticles();

        $video_first = !$articleItems->have_posts();
        if ($mediaItems->have_posts()) {

            $video_first = true;
            while ($mediaItems->have_posts()) {
                $mediaItems->the_post();
                $featuredLink = get_the_permalink();
                $featuredTitle = get_the_title();
                $featuredDesc = strip_tags(get_the_content());
                $featuredDescToUse = pjs_truncate($featuredDesc, 50);
                $featuredImg = get_field('image');
                $useImg = $featuredImg['url'];
                if (!$featuredImg) {
                    $useImg = get_template_directory_uri() . '/images/media-home.jpg';
                }
                echo '<a href="' . $featuredLink . '">';
                echo '<span class="info-bar">';
                echo '<span class="fm">Featured Media:</span>';
                echo '<span class="fm-info">';
                echo '<span class="title">' . $featuredTitle . '</span>';
                echo '<span class="desc">' . $featuredDescToUse . '</span>';
                echo '</span>';
                echo '</span>';
                echo '<img src="' . $useImg . '" alt="' . $featuredTitle . '" />';
                echo '</a>';
            }
        }
        else {
            echo "<h2>No publications found.</h2>";
        }
        wp_reset_query();

    }
}