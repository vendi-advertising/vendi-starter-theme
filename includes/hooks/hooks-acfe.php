<?php

add_action(
    'acfe/init',
    static function () {

        // Please perform all CPT and taxonomy work in the YAML files
        acfe_update_setting('modules/post_types', false);
        acf_update_setting('acfe/modules/taxonomies', false);

        // Please register all options in PHP
        acfe_update_setting('modules/options_pages', false);
    }
);
