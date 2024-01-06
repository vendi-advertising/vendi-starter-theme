<?php

add_action(
    'wp_head',
    static function () {
        $global_javascript = vendi_get_global_javascript('header');

        foreach ($global_javascript as $javascript) {
            echo $javascript;
        }
    }
);

add_action(
    'wp_footer',
    static function () {
        $global_javascript = vendi_get_global_javascript('footer');

        foreach ($global_javascript as $javascript) {
            echo $javascript;
        }
    }
);
