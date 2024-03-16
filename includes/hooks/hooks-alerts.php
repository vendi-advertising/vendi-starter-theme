<?php

add_action(
    'wp_footer',
    static function () {
        $args = [
            'post_type' => 'alert',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => 'start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '<=',
                    'type' => 'DATE',
                ],
                [
                    'key' => 'end_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ],
            ],
        ];

        if (!$alerts = get_posts($args)) {
            return;
        }

        if (!$alertsAsJson = vendi_convert_alerts_to_objects($alerts)) {
            return;
        }

        vendi_theme_enqueue_style('vendi-alerts', '/css/site-alerts.css');
        vendi_theme_enqueue_script('vendi-alerts', '/js/alerts.js', in_footer: true);
        wp_localize_script(
            'vendi-alerts',
            'vendi_alerts',
            [
                'alerts' => $alertsAsJson,
            ]
        );
    }
);
