<?php

add_action('init', function () {
    if ('production' === wp_get_environment_type()) {
        return;
    }

    register_post_type('component_examples', [
        'label'               => 'Component Examples',
        'labels'              => [
            'name'          => 'Component Examples',
            'singular_name' => 'Component Example',
            'add_new_item'  => 'Add New Component Example',
            'edit_item'     => 'Edit Component Example',
            'view_item'     => 'View Component Example',
            'search_items'  => 'Search Component Examples',
            'not_found'     => 'No component examples found',
        ],
        'public'              => true,
        'show_in_menu'        => true,
        'show_in_rest'        => true,
        'supports'            => ['title', 'editor', 'revisions'],
        'menu_icon'           => 'dashicons-layout',
    ]);
});
