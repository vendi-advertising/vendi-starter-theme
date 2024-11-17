<?php

use Symfony\Component\Filesystem\Path;

add_action(
    "acfe/flexible/render/before_template",
    static function ($field, $layout, $is_preview) {
        if (!$is_preview) {
            return;
        }

        static $supportedLayouts = [
            'hero' => [
                'hero-single',
            ],
            'content_components' => [
                'accordion',
                'basic_copy',
                'blockquote',
                'callout',
                'columns',
                'figure',
                'form',
                'group',
                'image_gallery',
                'showcase',
                'stats',
                'stepwise',
                'testimonial',
            ],
        ];

        $thisFieldName = $field['name'];
        $thisLayoutName = $layout['name'];

        if (!array_key_exists($thisFieldName, $supportedLayouts)) {
            return;
        }

        if (!in_array($thisLayoutName, $supportedLayouts[$thisFieldName], true)) {
            return;
        }

        vendi_load_component_v3('core/dynamic-render', ['field' => $field, 'layout' => $layout, 'is_preview' => $is_preview]);
    },
    accepted_args: 3,
);
