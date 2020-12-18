<?php

use WebPConvert\Convert\Converters\Stack;

add_action(
    'fly_image_created',
    static function ($attachment_id, $fly_file_path) {
        $webp_file_path = $fly_file_path . '.webp';
        Stack::convert(
            $fly_file_path,
            $webp_file_path,
            $options = [

                // PS: only set converters if you have strong reasons to do so
                'converters' => [
                    'cwebp', 'vips', 'imagick', 'gmagick', 'imagemagick', 'graphicsmagick', 'wpc', 'gd'
                ],

                // Any available options can be set here, they dribble down to all converters.
                'metadata' => 'all',
            ]
        );
    },
    10,
    2
);
