<?php

add_filter(
    'vendi/component-loader/get-layout-folder',
    static function(){
        return VENDI_CUSTOM_THEME_LAYOUT_FOLDER_NAME;
    }
);
