<?php

namespace Vendi\Theme;

use RuntimeException;
use Symfony\Component\Filesystem\Path;
use WP_Styles;

class VendiDynamicRenderHelper
{
    private function __construct()
    {
        // NOOP
    }

    public static function getDynamicHtml(): string
    {
        $vendiDynamicRenderHelper = new self;

        $backup = $vendiDynamicRenderHelper->backupGlobalWordPressStyles();

        // Always load theme core
        $vendiDynamicRenderHelper->loadCoreComponentForStyles();

        $html    = $vendiDynamicRenderHelper->renderComponentToBuffer();
        $cssText = $vendiDynamicRenderHelper->getBufferedCss();

        $vendiDynamicRenderHelper->restoreGlobalWordPressStyles($backup);

        return "<html class=\"preview\" lang=\"en-us\"><style>$cssText</style>$html</html>";
    }

    /**
     * We need to load per-component CSS, but we also don't want to change the render system
     * to test whether it is in preview mode or not. So we'll continue to enqueue styles
     * normally, but we'll use a backup copy of the global $wp_styles to get the files so
     * that we can reset it each time. We're also going to load from disk instead of from
     * URL to reduce the number of HTTP requests.
     *
     * NOTE: For anything that registers in one pass, and then enqueues in another pass, this
     * could break. It should be pretty easy to clone the registered styles over, however, or
     * clone the entire object, and reset the queue.
     */
    private function backupGlobalWordPressStyles(): WP_Styles
    {
        // Backup the global $wp_styles and replace it with a fresh copy
        global $wp_styles;

        $backup_wp_styles = $wp_styles;
        $wp_styles        = new WP_Styles();

        return $backup_wp_styles;
    }

    private function restoreGlobalWordPressStyles(WP_Styles $backup_wp_styles): void
    {
        global $wp_styles;

        $wp_styles = $backup_wp_styles;
    }

    /**
     * Inspect all enqueue CSS files and try to map to disk to get the raw CSS
     */
    private function getBufferedCss(): string
    {
        global $wp_styles;

        // Grab the individual CSS files
        $cssText = [];
        foreach ($wp_styles->queue as $handle) {
            if ( ! array_key_exists($handle, $wp_styles->registered)) {
                continue;
            }

            if ( ! $url = $wp_styles->registered[$handle]->src) {
                continue;
            }

            if ( ! is_string($url)) {
                continue;
            }

            if ( ! $path = parse_url($url, PHP_URL_PATH)) {
                continue;
            }

            $absolutePath = Path::join(ABSPATH, $path);
            if ( ! is_readable($absolutePath)) {
                continue;
            }

            $cssText[] = file_get_contents($absolutePath);
        }

        return implode("\n", $cssText);
    }

    private function loadCoreComponentForStyles(): void
    {
        // NOTE: This is not going to the buffer, we just need it to get styles registered
        vendi_load_component_v3('core');
    }

    private function renderComponentToBuffer(): false|string
    {
        // Re-grab parameters from the filter
        global $vendi_component_object_state;
        ['field' => $field, 'layout' => $layout] = $vendi_component_object_state;

        $thisFieldName = $field['name'];

        /* To not have the component be aware that it is rendering in preview mode, load it normally but buffer it.
        *
        * NOTE: Each component system needs to be manually handled here for now.
        */
        ob_start();
        switch ($thisFieldName) {
            case 'content_components':
            case 'column_content':
                vendi_load_component_v3($layout['name']);
                break;
            case 'hero':
                vendi_load_component_v3('hero');
                break;
            case 'cards':
                if ($layout['name'] === 'simple_cards') {
                    vendi_load_component_v3('action_cards/simple_cards');
                }
                break;
            case 'accordion_items':
                // When previewing this subcomponent we want to open it on render, since we can't interact
                // with it. But we don't want to open it when previewing the parent component, which means
                // we can't use ACF's native $is_preview variable.
                global $vendi_accordion_item_preview;
                $vendi_accordion_item_preview = true;
                vendi_load_component_v3('accordion/accordion_item');
                unset($vendi_accordion_item_preview);
                break;
            default:
                throw new RuntimeException('Unknown field name:', $thisFieldName);
        }

        return ob_get_clean();
    }
}
