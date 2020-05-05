<?php

use Vendi\CptFromYaml\CptLoader;

add_action(
    'init',
    static function () {
        CptLoader::register_all_cpts();
    },
    0
);
