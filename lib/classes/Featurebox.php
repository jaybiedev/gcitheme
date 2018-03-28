<?php
require_once(get_template_directory() . '/lib/classes/AbstractComponent.php');
require_once(get_template_directory() . '/lib/classes/Post.php');

class Featurebox extends AbstractComponent  {

    public $post_id;
    public $image_url;
    public $title;
    public $excerpt;
    public $description;
    public $Post;

    function __construct($post_id) {
        $this->post_id = $post_id;

        if (!empty($post_id))
            $this->Post = new Post($post_id);
        /*
         * $title = get_post(get_post_thumbnail_id())->post_title; //The Title
         * $caption = get_post(get_post_thumbnail_id())->post_excerpt; //The Caption
         * $description = get_post(get_post_thumbnail_id())->post_content; // The Descriptio
         */
    }


    function render() {

        //if ( ! get_post($this->post_id) )
          //  return '';

        //$image_url = get_the_post_thumbnail($this->post_id, $size = 'post-thumbnail');
        // $title = get_the_title($this->post_id);
        return $this->getContent();
    }

    function getImage() {

        //
    }

    function getTitle() {


        return $title;
    }

    function getContent() {

        $caption = substr($this->Post->caption, 0,100);
        if (strlen($this->Post->caption) > 100)
            $caption .= "...";

        //             <img src="{$this->Post->thumbnail_url}" class="img-fluid" alt="{$this->Post->post_title}">

        $html =<<<HTML
<div class="card">

        <!--Card image-->
        <div class="view overlay" style="background:url('{$this->Post->thumbnail_url}')">
            <a href="#">
                <div class="mask rgba-white-slight"></div>
            </a>
        </div>

        <!--Card content-->
        <div class="card-body">
            <!--Title-->
            <h4 class="card-title">{$this->Post->post_title}</h4>
            <!--Text-->
            <p class="card-text">{$caption}</p>
            <a href="{$this->Post->permalink}" class="btn btn-secondary">LEARN MORE</a>
        </div>

    </div>
HTML;

        return $html;
    }

    public static function getFeaturedPostIds($post_id)
    {
        $meta = get_post_meta($post_id);
        $featured_post_ids = preg_split('@[\s,]+@', $meta['featured_ids'][0], NULL, PREG_SPLIT_NO_EMPTY);
        return $featured_post_ids;
    }

}