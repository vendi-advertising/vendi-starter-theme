<?php

use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use Symfony\Component\Filesystem\Path;

add_action(
    "acfe/flexible/render/before_template",
    static function ($field, $layout, $is_preview) {
        if ( ! $is_preview) {
            return;
        }

        if ( ! $thisFieldName = ($field['name'] ?? null)) {
            return;
        }

        if ( ! $thisLayoutName = ($layout['name'] ?? null)) {
            return;
        }

        $readme = Path::join(
            VENDI_CUSTOM_THEME_PATH,
            VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME,
            $thisLayoutName,
            'readme.md',
        );

        if (is_readable($readme)) {
            $frontMatterParser = new FrontMatterParser(new SymfonyYamlFrontMatterParser());
            $result            = $frontMatterParser->parse(file_get_contents($readme));

            if (($frontMatter = $result->getFrontMatter()) && ($parentField = ($frontMatter['parent-field'] ?? null)) && ($parentField === $thisFieldName)) {
                vendi_load_component_v3('core/dynamic-render', ['field' => $field, 'layout' => $layout, 'is_preview' => $is_preview]);

                return;
            }
        }

        // Instead of registering components here, you can set the front matter key "parent-field" in the readme.md
        // file of the component.

        static $supportedLayouts = [
            'hero'               => [
                'hero-single',
                'no_hero',
                'home_hero',
            ],
            'link_rows'          => [
                'link_row',
            ],
            'cards'              => [
                'simple_link',
            ],
            'content_components' => [
                'accordion',
                'action_cards',
                'basic_copy',
                'blockquote',
                'call_to_action',
                'callout_blocks',
                'carousel',
                'columns',
                'events',
                'figure',
                'floating_callout',
                'form',
                'group',
                'image_gallery',
                'link_column',
                'link_ladder',
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

add_action(
    'vendi/component-loader/style-inline',
    static function ($inlineStyle, $id) {
        global $vendi_inline_style_buffer;
        if ( ! is_array($vendi_inline_style_buffer)) {
            $vendi_inline_style_buffer = [];
        }

        $vendi_inline_style_buffer[$id] = $inlineStyle;
    },
    accepted_args: 2,
);
