<?php

class PostViewsCounter {
    public static function init() {
        add_action('wp_head', array(__CLASS__, 'countPostView'));
        add_action('rest_api_init', array(__CLASS__, 'registerPostViewsField'));
    }

    public static function countPostView() {
        if (is_single() || is_page()) {
            $post_id = get_the_ID();
            $views = get_post_meta($post_id, '_post_views', true);
            $views = intval($views);
            $views++;

            update_post_meta($post_id, '_post_views', $views);
        }
    }

    public static function registerPostViewsField() {
        register_rest_field(
            array('post', 'page'), // 'post' ve 'page' türündeki içerikleri dahil et
            'post_views',
            array(
                'get_callback' => array(__CLASS__, 'getPostViews'),
                'schema' => null,
            )
        );
    }

    public static function getPostViews($object, $field_name, $request) {
        $post_id = $object['id'];
        $views = get_post_meta($post_id, '_post_views', true);
        return intval($views);
    }
}

// Sınıfı başlat
PostViewsCounter::init();

//Example : get_post_meta($post_id, '_post_views', true);
