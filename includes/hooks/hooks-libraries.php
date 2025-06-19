<?php

add_action(
    'vendi/component-loader/load-custom-script-asset',
    static function ($assetName) {
        switch ($assetName) {
            case '@jquery':
                wp_enqueue_script('jquery');

                return;
            case '@slick':
                vendi_load_component_v3('core/libraries/slick');

                return;
        }
    },
);


add_action(
    'vendi/component-loader/load-custom-style-asset',
    static function ($assetName) {
        switch ($assetName) {
            case '@slick':
                vendi_load_component_v3('core/libraries/slick');

                return;
        }
    },
);
