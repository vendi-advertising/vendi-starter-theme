<?php


add_filter(
    'wp_nav_menu_main-menu_items',
    static function ($items, $args) {
        ob_start();
        vendi_load_component_v3('header/search-button');
        $search_button = ob_get_clean();

        $items .= <<<EOT
            <li class="menu-item menu-item-search-form">
                {$search_button}
            </li>
            EOT;

        return $items;
    },
    10,
    2,
);
