<?php

// Setup some theme-level constants to make life easier elsewhere
const VENDI_CUSTOM_THEME_FILE = __FILE__;
const VENDI_CUSTOM_THEME_PATH = __DIR__;
define('VENDI_CUSTOM_THEME_URL', get_bloginfo('template_directory'));
define('VENDI_CUSTOM_THEME_URL_WITH_NO_TRAILING_SLASH', untrailingslashit(VENDI_CUSTOM_THEME_URL));
define('VENDI_CUSTOM_THEME_FEATURE_PATH', VENDI_CUSTOM_THEME_PATH.'/features');

require_once VENDI_CUSTOM_THEME_PATH.'/includes/constants.php';
require_once VENDI_CUSTOM_THEME_PATH.'/includes/theme-validity-test.php';
require_once VENDI_CUSTOM_THEME_PATH.'/includes/autoload.php';

// Generally shared across all themes
require_once VENDI_CUSTOM_THEME_PATH.'/includes/vendi-base.php';

// Global functions
require_once VENDI_CUSTOM_THEME_PATH.'/includes/global-functions.php';

// General hooks
require_once VENDI_CUSTOM_THEME_PATH.'/includes/hooks.php';

require_once VENDI_CUSTOM_THEME_PATH.'/includes/error-handling.php';
add_filter(
    'post_row_actions',
    static function ($actions, $post) {
        if ($post->post_type === "__component-docs") {
            if ($component_slug = get_field('component_slug', $post->ID)) {
                $component_slug = preg_replace('/[^a-zA-Z0-9-_]/', '', $component_slug);
            }

            if ($component_slug) {
                $actions['view-documentation'] = sprintf(
                    '<a href="/__theme_docs/%1$s">View component documentation</a>',
                    $component_slug,
                );
            }
        }

        return $actions;
    },
    accepted_args: 2,
);
add_action(
    'add_meta_boxes',
    static function () {
        add_meta_box(
            'component-docs-documentation-link',
            'Documentation Link',
            static function () {
                global $post;

                if ($component_slug = get_field('component_slug', $post->ID)) {
                    $component_slug = preg_replace('/[^a-zA-Z0-9-_]/', '', $component_slug);
                }
                ?>
                <?php if ( ! $component_slug): ?>
                    <p class="description">Please set the slug and save the documentation to enable the link.</p>
                <?php else: ?>
                    <a href="/__theme_docs/<?php echo $component_slug; ?>">View component documentation</a>
                <?php endif; ?>
                <?php
            },
            '__component-docs',
            'normal',
            'high',
        );
    },
);
