<?php /** @noinspection SpellCheckingInspection */

//Remove extra HTTP headers send by WordPress
add_filter(
    'wp_headers',
    static function ($headers, $context) {
        unset($headers['X-Pingback']);

        return $headers;
    },
    9999,
    2,
);

//This code is called at theme boot up
add_action(
    'after_setup_theme',
    static function () {
        //Remove a bunch of things from the HTML head tags
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'wp_shortlink_wp_head');

        //Emoji
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'parent_post_rel_link');
        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
        remove_action('wp_head', 'noindex');
        remove_action('wp_head', 'rel_canonical');

        remove_theme_support('automatic-feed-links');

        //Remove another HTTP Header
        remove_action('template_redirect', 'wp_shortlink_header', 11);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('template_redirect', 'rest_output_link_header', 11);
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_resource_hints', 2);

        remove_action('wp_footer', 'the_block_template_skip_link');
    },
);

add_action(
    'wp_footer',
    static function () {
        wp_deregister_script('wp-embed');
    },
);
