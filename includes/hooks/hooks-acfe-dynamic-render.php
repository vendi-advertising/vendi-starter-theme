<?php

add_action(
    "acfe/flexible/render/before_template",
    static function ($field, $layout, $is_preview) {
        if ( ! $is_preview) {
            return;
        }

        static $supportedLayouts = [
            'hero'               => [
                'hero-single',
                'no_hero',
                'home_hero',
            ],
            'cards'              => [
                'simple_cards',
            ],
            'content_components' => [
                'accordion',
                'action_cards',
                'basic_copy',
                'blockquote',
                'call_to_action',
                'callout',
                'carousel',
                'columns',
                'events',
                'figure',
                'form',
                'group',
                'image_gallery',
                'people',
                'showcase',
                'spacer',
                'stats',
                'stepwise',
                'testimonial',
            ],
            'column_content'     => [
                'accordion',
                'basic_copy',
                'blockquote',
                'callout',
                'figure',
                'form',
                'image_gallery',
                'stepwise',
                'testimonial',
            ],
            'accordion_items'    => [
                'accordion_item',
            ],
        ];

        $thisFieldName  = $field['name'];
        $thisLayoutName = $layout['name'];

        if ( ! array_key_exists($thisFieldName, $supportedLayouts)) {
            return;
        }

        if ( ! in_array($thisLayoutName, $supportedLayouts[$thisFieldName], true)) {
            return;
        }

        vendi_load_component_v3('core/dynamic-render', ['field' => $field, 'layout' => $layout, 'is_preview' => $is_preview]);
    },
    accepted_args: 3,
);
