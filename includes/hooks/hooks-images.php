<?php

use Webmozart\PathUtil\Path;
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
                    'cwebp',
                    'vips',
                    'imagick',
                    'gmagick',
                    'imagemagick',
                    'graphicsmagick',
                    'wpc',
                    'gd'
                ],

                // Any available options can be set here, they dribble down to all converters.
                'metadata' => 'none',

                'png' => [
                    'encoding' => 'auto',    /* Try both lossy and lossless and pick smallest */
                    'near-lossless' => 80,   /* The level of near-lossless image preprocessing (when trying lossless) */
                    'quality' => 85,         /* Quality when trying lossy. It is set high because pngs is often selected to ensure high quality */
                ],
                'jpeg' => [
                    'encoding' => 'auto',     /* If you are worried about the longer conversion time, you could set it to "lossy" instead (lossy will often be smaller than lossless for jpegs) */
                    'quality' => 'auto',      /* Set to same as jpeg (requires imagick or gmagick extension, not necessarily compiled with webp) */
                    'max-quality' => 85,      /* Only relevant if quality is set to "auto" */
                    'default-quality' => 85,  /* Fallback quality if quality detection isn't working */
                ]
            ]
        );
    },
    10,
    2
);

/**
 * Validate the items that will be placed into a <picture> tag to make
 * sure that they actually exist.
 */
add_filter(
    'vendi/picture-tag/images',
    static function ($images, $attachment_id, $size, $crop, $attr) {
        foreach ($images as $key => $img) {
            // The $img variable is either a string with a full HTML tag, or an array with a srcset value,
            // we only want to process the latter because it is assumed that WordPress is taking care
            // of the former.
            $srcset = $img['srcset'] ?? null;
            if (!$srcset) {
                continue;
            }

            // Grab the path of the image
            $path = wp_parse_url($srcset, PHP_URL_PATH);
            if (!$path) {
                continue;
            }

            // Assume that the path is relative to the site's root, and create an absolute version of that.
            $absPath = Path::join(ABSPATH, $path);

            // See if it exists on disk
            if (!is_readable($absPath)) {
                unset($images[$key]);
                continue;
            }
        }

        return $images;
    },
    10,
    5
);
