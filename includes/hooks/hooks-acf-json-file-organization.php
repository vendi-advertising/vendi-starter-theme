<?php

use Vendi\Theme\VendiComponentsAcfMagicFolderEnum;

add_filter(
    'acf/json/save_file_name',
    static function ($filename, $post, $load_path) {
        static $b = [
            VendiComponentsAcfMagicFolderEnum::COMPONENTS->value     => [
                'group_6599ece5e2264.json' => 'accordion.json',
                'group_6757af2f78488.json' => 'action-cards.json',
                'group_659c0a262f75a.json' => 'basic-copy.json',
                'group_659c754d6a1d2.json' => 'blockquote.json',
                'group_677ed68366ed5.json' => 'call-to-action.json',
                'group_659c272fb8f1f.json' => 'callout.json',
                'group_67b891ce2ddbf.json' => 'carousel.json',
                'group_6695804de3410.json' => 'columns.json',
                'group_677f517d19283.json' => 'events.json',
                'group_659c6e7fc386c.json' => 'figure.json',
                'group_66aa473bce3b3.json' => 'form.json',
                'group_67327a78b71a5.json' => 'group.json',
                'group_66aa7a9bdb070.json' => 'hero.json',
                'group_66acef245de12.json' => 'image-gallery.json',
                'group_661d782329c85.json' => 'image-grid.json',
                'group_675772dd4896f.json' => 'people.json',
                'group_659c445704039.json' => 'reusable-content.json',
                'group_67b62a9037341.json' => 'spacer.json',
                'group_66a9355627020.json' => 'stats.json',
                'group_661989c1d0e74.json' => 'stepwise.json',
                'group_6599ae10d9002.json' => 'testimonial.json',
            ],
            VendiComponentsAcfMagicFolderEnum::ENTITIES->value       => [
                'post_type_66ba8537bd649.json' => 'training-hub.json',
                'post_type_6599ab6f6b61a.json' => 'testimonial.json',
                'post_type_65f3571fbc97e.json' => 'alert.json',
                'post_type_659c2c3567923.json' => 'reusable-content.json',
                'post_type_6691845c67ba8.json' => 'reusable-background.json',
                'post_type_67576ddd0957f.json' => 'person.json',
                'post_type_6757a9165d948.json' => 'events.json',

            ],
            VendiComponentsAcfMagicFolderEnum::BACKGROUNDS->value    => [
                'group_66902182309cc.json' => 'background-color.json',
                'group_66901f65435d7.json' => 'background-image.json',
                'group_669022c556e50.json' => 'background-linear-gradient.json',
                'group_66918627662b4.json' => 'background-reusable-background.json',
                'group_66a80a6a0ee49.json' => 'background-video.json',
            ],
            VendiComponentsAcfMagicFolderEnum::FIELDSETS->value      => [
                'group_66901f4b7739f.json' => 'backgrounds.json',
                'group_66ba1140c9524.json' => 'content-area-settings.json',
                'group_67813cce98c2f.json' => 'image-settings.json',
            ],
            VendiComponentsAcfMagicFolderEnum::THEME_SETTINGS->value => [
                'group_61d8a393c5415.json' => 'theme-settings-fields.json',

                'group_62348a640fc68.json' => 'theme-settings-tab-global-javascript.json',

                'group_661435d8a956b.json' => 'theme-settings-sso.json',
                'group_6615bc1d65360.json' => 'theme-settings-sso-provider-github.json',
                'group_66143631ddd8b.json' => 'theme-settings-sso-provider-azure.json',
                'group_6619abc83577b.json' => 'theme-settings-sso-provider-google.json',

                'group_67c0985f6b99a.json' => 'theme-settings-login.json',
            ],
            VendiComponentsAcfMagicFolderEnum::BASE_COMPONENT->value => [
                'group_66aced8597513.json' => 'base-component.json',
                'group_66fc4b4a790ac.json' => 'base-component-tab-header.json',
                'group_66fc4b1253bca.json' => 'base-component-tab-background.json',
                'group_66fc4a1a9dd82.json' => 'base-component-tab-content-settings.json',
            ],
            VendiComponentsAcfMagicFolderEnum::FIELDS->value         => [
                'group_66a94415b1615.json' => 'color.json',
                'group_66aa465159585.json' => 'content-placement.json',
                'group_66aa45d83feeb.json' => 'content-width.json',
            ],
            VendiComponentsAcfMagicFolderEnum::ENTITY_FIELDS->value  => [
                'group_6599abaa59f84.json' => 'testimonial-fields.json',
                'group_65f357e01af5b.json' => 'alert-fields.json',
                'group_6691856be84f6.json' => 'reusable-background-fields.json',
                'group_67576e294c740.json' => 'person-fields.json',
                'group_6757ad37e0660.json' => 'event-fields.json',
                'group_67be4eb272a18.json' => 'shared-content-settings.json',
            ],
            VendiComponentsAcfMagicFolderEnum::OPTION_PAGES->value   => [
                'ui_options_page_66143503e5c73.json' => 'option-page-theme-settings.json',
                'ui_options_page_661435b551158.json' => 'option-page-theme-settings-sso.json',
                'ui_options_page_67c0983f69e94.json' => 'option-page-theme-settings-login.json',
            ],

            VendiComponentsAcfMagicFolderEnum::SETTINGS_MODAL->value => [
                'group_669020d3ad6b8.json' => 'background.json',
            ],

            VendiComponentsAcfMagicFolderEnum::SUBCOMPONENTS->value => [
                'group_6757afba28703.json' => 'action-cards--simple.json',
                'group_67b893aa7e78c.json' => 'carousel--image-slide.json',
                'group_66aa78fee778b.json' => 'hero--simple.json',
                'group_67b8ee4701845.json' => 'hero--no-hero.json',
                'group_67b8efde47be7.json' => 'hero--home.json',
            ],
        ];

        foreach ($b as $folder => $mapping) {
            if (array_key_exists($filename, $mapping)) {
                return $folder . VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER . $mapping[$filename];
            }
        }

        static $mapping = [

            'group_66195515aacef.json' => 'accordion-item-shared.json',


            'group_661970fa6651c.json' => 'sub-component-call-to-action-button.json',
            'group_6616dde078cf0.json' => 'sub-component-grid-component-wrapper.json',
            'group_66198b6ededc6.json' => 'sub-component-stepwise-item-simple.json',
            'group_661d79b2aeb6d.json' => 'sub-component-image-grid-item.json',

            'group_6616f4a3c869c.json' => 'settings-modal-grid-settings.json',
            'group_6616f3340ebca.json' => 'settings-modal-grid-component.json',
            'group_6616f42278eda.json' => 'settings-modal-grid-row.json',
            'group_661809923e61a.json' => 'settings-modal-components.json',

            'group_6599af50880a8.json' => 'components-shared-component-settings-tab.json',
            'group_61dc8e97982a7.json' => 'content-components.json',


            'group_661983686d448.json' => 'tab-fields-headings.json',
        ];

        if (array_key_exists($filename, $mapping)) {
            return $mapping[$filename];
        }

        return $filename;
    },
    accepted_args: 3,
);
