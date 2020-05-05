<?php

//Load CSS and JavaScript
use Vendi\VendiAssetLoader\Loader;

add_action(
    'wp_enqueue_scripts',
    static function () {
        Loader::enqueue_default();
    }
);
