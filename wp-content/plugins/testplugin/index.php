<?php
/*
Plugin Name: Popular Post
Description: Plugin that serves a shortcode for displaying the post with the most comments.
Author: Vladislav Unterberg
Version: 0.0.1
*/

add_action( 'plugin_loaded', 'pplr_loaded' );
function pplr_loaded( $plugin ){
    add_image_size( 'custom-size', 200, 100, true );
}

add_action('wp_enqueue_scripts', 'pplr_scripts');
function pplr_scripts() {
    wp_enqueue_style( 'pplr-style', plugins_url('css/style.css', __FILE__) );
}

add_shortcode( 'popularPost', 'pplr_shortcode' );
function pplr_shortcode() {
    global $post;
    $result = "";

    $args = array(
        'post_type' => 'post',
        'numberposts' => 1,
        'orderby' => 'comment_count'
    );

    $popularPost = get_posts($args);

    if($popularPost) {
        foreach ($popularPost as $key => $post) {
            setup_postdata($post);

            $title = get_the_title();
            $thumb = get_the_post_thumbnail( get_the_ID(), 'custom-size' );
            $excerpt = get_the_excerpt();
            $date = get_the_date();

            $comments_number = get_comments_number();
            if ( $comments_number > 1 ) {
                $comments = $comments_number . __(' Comments');
            }
            elseif ( $comments_number == 1 ) {
                $comments = __('1 Comment');
            }

            $result = "<article class='pplr__post' id='post-{$post->ID}'>
                <div class='pplr__image'>
                    {$thumb}
                </div>
                <h2 class='pplr__title'>
                    {$title}
                </h2>
                <div class='pplr__content'>
                    {$excerpt}
                </div>
                <div class='pplr__footer'>
                    <span class='pplr__date'>
                        {$date}
                    </span>
                    <span class='pplr__comments'>
                        {$comments}
                    </span>
                </div>
            </article>";
        }
        wp_reset_postdata();
    }

    return $result;
}

