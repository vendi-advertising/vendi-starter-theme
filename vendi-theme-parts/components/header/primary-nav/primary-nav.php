<?php

add_filter(
    'wp_nav_menu_objects',
    static function ($items, $args) {
        // loop
        foreach ($items as $item) {
            // vars
            if ($menu_display_mode = get_field('menu_display_mode', $item)) {
                $item->classes[] = 'menu-item-display-mode-' . $menu_display_mode;
            }
        }

        // return
        return $items;
    },
    accepted_args: 2,
);


wp_nav_menu([
    'theme_location'  => 'primary_navigation',
    'container'       => 'nav',
    'container_class' => 'main-nav-wrap',
    'menu_class'      => 'main-nav list-as-nav', //        'fallback_cb'     => 'false',
    'depth'           => 3,
    'before'          => '<span class="menu-item-text-wrapper">',
    'after'           => '</span>',
],);
