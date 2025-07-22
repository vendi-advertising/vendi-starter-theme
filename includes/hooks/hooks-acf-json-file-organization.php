<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\VendiComponentsAcfMagicFolderEnum;

add_filter(
    'acf/json/save_file_name',
    static function ($filename) {
        static $b = [

            // DO NOT use VendiComponentsAcfMagicFolderEnum::COMPONENTS
            // or VendiComponentsAcfMagicFolderEnum::SUBCOMPONENTS
            // anymore, use the modern filesystem-based approach which stores
            // the ACF in the component folder itself.
            VendiComponentsAcfMagicFolderEnum::COMPONENTS->value     => [],
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

//                'group_661435d8a956b.json' => 'theme-settings-sso.json',
//                'group_6615bc1d65360.json' => 'theme-settings-sso-provider-github.json',
//                'group_66143631ddd8b.json' => 'theme-settings-sso-provider-azure.json',
//                'group_6619abc83577b.json' => 'theme-settings-sso-provider-google.json',

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
//                'ui_options_page_661435b551158.json' => 'option-page-theme-settings-sso.json',
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
            // Intentionally skipped over for now, will be removed when it is determined
            // it is no longer being used anywhere else.
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
);

/*
 * The sanitize_file_name messes with files that have multiple file extensions.
 * Any "internal" extension that is not a registered MIME type gets an underscore
 * add to it, so "example.acf.json" becomes "example.acf_.json". We could address
 * this by registering a MIME type for ".acf" but that would be a bit of a hack,
 * and would technically allow someone to upload a file with the ".acf" extension
 * which is nonsensical. Instead, we'll blinding assume that anyone that is being
 * tripped up by this would like to have it corrected.
 */
add_filter(
    'sanitize_file_name',
    static function ($filename) {
        if (str_ends_with($filename, '.acf_.json')) {
            $filename = str_replace('.acf_.json', '.acf.json', $filename);
        }

        return $filename;
    },
);

/*
 * Register our individual component folders.
 */
add_filter(
    'acf/json/load_paths',
    static function ($paths) {
        return array_merge($paths, vendi_get_component_acf_paths(true));
    },
);

/* There is no filter in ACF that I've found so far that supplies the key
 * when trying to determine the save file name. There's a hook, but you have
 * to know the key in advance, and the only way to safely do that is to load
 * in a WordPress early hook.
 */
add_action(
    'init',
    static function () {
        /** @var MappingInfo[] $componentPaths */
        $componentPaths = vendi_get_component_acf_paths();

        foreach ($componentPaths as $vendiKey => $vendiPathObject) {
            add_filter(
                "acf/settings/save_json/key={$vendiKey}",
                static function () use ($vendiKey, $vendiPathObject) {
                    // If this is set up correctly, there's one and only one correct path for saving.
                    return $vendiPathObject->dir;
                },
            );
        }

        add_filter(
            'acf/json/save_file_name',
            static function ($filename) use ($componentPaths) {
                // If the legacy system is already handling it, keep it that way
                if (str_contains($filename, VENDI_COMPONENTS_ACF_MAGIC_FOLDER_PATH_IDENTIFIER)) {
                    return $filename;
                }

                foreach ($componentPaths as $vendiKey => $vendiPathObject) {
                    // Force the file name
                    if ($vendiKey . '.json' === $filename) {
                        return $vendiPathObject->filename;
                    }
                }

                return $filename;
            },
        );
    },
);


function vendi_get_component_acf_paths(bool $dirsOnly = false): array
{
    static $key_to_directory_mapping = null;
    if (null === $key_to_directory_mapping) {
        $key_to_directory_mapping = [];

        class MappingInfo
        {
            public readonly string $dir;
            public readonly string $filename;

            public function __construct(
                public readonly string $absolutePathToJsonFile,
                public readonly string $key,
            ) {
                $this->dir      = dirname($absolutePathToJsonFile);
                $this->filename = basename($absolutePathToJsonFile);
            }
        }

        $componentsPath = Path::join(VENDI_CUSTOM_THEME_PATH . VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME);
        if ($files = glob($componentsPath . '/*/*.acf.json')) {
            foreach ($files as $file) {
                try {
                    $json = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $e) {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        /** @noinspection PhpUnhandledExceptionInspection */
                        throw $e;
                    }

                    // Swallow the error in non-debug mode
                    continue;
                }

                if ($key = ($json['key'] ?? null)) {
                    $key_to_directory_mapping[$key] = new MappingInfo($file, $key);
                }
            }
        }

        // Subcomponents
        if ($files = glob($componentsPath . '/*/*.assets.json')) {
            foreach ($files as $file) {
                $dir = dirname($file);
                try {
                    $json = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $e) {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        /** @noinspection PhpUnhandledExceptionInspection */
                        throw $e;
                    }

                    // Swallow the error in non-debug mode
                    continue;
                }

                if ($subcomponents = ($json['subcomponents'] ?? null)) {
                    foreach ($subcomponents as $subcomponent) {
                        $key  = ($subcomponent['key'] ?? null);
                        $file = ($subcomponent['file'] ?? null);
                        if ( ! $key || ! $file) {
                            continue;
                        }

                        $key_to_directory_mapping[$key] = new MappingInfo(Path::join($dir, $file), $key);
                    }
                }
            }
        }
    }

    if ($dirsOnly) {
        return array_column($key_to_directory_mapping, 'dir');
    }

    return $key_to_directory_mapping;
}
