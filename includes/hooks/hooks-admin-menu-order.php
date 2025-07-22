<?php

add_filter(
    'custom_menu_order',
    static fn() => true,
);

add_action(
    'admin_menu',
    static function () {
        global $menu;
        $menu[] = ['', 'read', 'separator-location', '', 'wp-menu-separator'];
        $menu[] = ['', 'read', 'separator-additional', '', 'wp-menu-separator'];
    },
);


add_filter(
    'menu_order',
    static function ($menu_order) {
        // This order is not prescriptive
        $groups = [
            'top'        => [
                'index.php',
                'separator1',
                'edit.php?post_type=page',
                'gf_edit_forms',
                'upload.php',
            ],
            'location'   => [
                'separator-location',
                'edit.php?post_type=office',
                'edit.php?post_type=county',
                'edit.php?post_type=person',
            ],
            'additional' => [
                'separator-additional',
                'edit.php?post_type=news',
                'edit.php?post_type=alert',
                'edit.php?post_type=_background',
                'edit.php?post_type=reusable-content',
                'edit.php?post_type=testimonial',
            ],
        ];

        // The separators are explicitly added here to ensure that they
        // are at the top of the menu group
        $new_menu_order = [
            'top'        => [],
            'location'   => ['separator-location'],
            'additional' => ['separator-additional'],
        ];

        foreach ($menu_order as $idx => $menu_item) {
            foreach ($groups as $group_name => $group_items) {
                if (in_array($menu_item, $group_items, true)) {
                    if ( ! in_array($menu_item, $new_menu_order[$group_name], true)) {
                        $new_menu_order[$group_name][] = $menu_item;
                    }
                    unset($menu_order[$idx]);
                }
            }
        }

        $final = [];
        foreach ($new_menu_order as $group) {
            $final = array_merge($final, $group);
        }

        $final = array_merge($final, $menu_order);

        return $final;
    },
);
