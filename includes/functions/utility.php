<?php

use Symfony\Component\Filesystem\Path;

function vendi_register_image_preload($url, $fetchPriority = 'high'): void
{
    global $vendi_preloads;
    if ( ! is_array($vendi_preloads)) {
        $vendi_preloads = [];
    }

    $vendi_preloads[$url] = [
        'priority' => $fetchPriority,
    ];
}

function vendi_create_excerpt_from_content_components($postToUse, int $wordCount = 55): string
{
    $postToUse = get_post($postToUse);

    // This is needed for things like "the_title" and such
    global $post;
    $post = $postToUse;

    setup_postdata($post);

    // The component system uses get_queried_object(). We could override it on that side,
    // but historically that has lead to problems, so we're just going to do it here.
    global $wp_query;
    $old_wp_query                = $wp_query;
    $wp_query                    = new WP_Query();
    $wp_query->queried_object    = $post;
    $wp_query->queried_object_id = $post->ID;

    ob_start();

    vendi_load_component_v3('components');

    $ret = ob_get_clean();
    $ret = wp_strip_all_tags($ret);
    $ret = wp_trim_words($ret, $wordCount);
    wp_reset_postdata();

    // Restore the global query
    $wp_query = $old_wp_query;

    return $ret;
}

function vendi_unparse_url($parsed_url): string
{
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host     = $parsed_url['host'] ?? '';
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user     = $parsed_url['user'] ?? '';
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
    $pass     = ($user || $pass) ? "$pass@" : '';
    $path     = $parsed_url['path'] ?? '';
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

    return "$scheme$user$pass$host$port$path$query$fragment";
}

function vendi_get_image_url(string $pathRelativeToThemeFolder, bool $echo = true): ?string
{
    $path = Path::join(VENDI_CUSTOM_THEME_URL, $pathRelativeToThemeFolder);

    if ($echo) {
        echo esc_url($path);
    }

    return $path;
}

function vendi_get_svg(string $pathRelativeToThemeFolder, bool $echo = true, bool $cache = true, ?bool $includeSourcePath = null, bool $pathIsAbsolute = false): ?string
{
    if ($echo) {
        $includeSourcePath = is_null($includeSourcePath) ? defined('WP_DEBUG') && WP_DEBUG : $includeSourcePath;
        if ($includeSourcePath) {
            echo sprintf('<!-- SVG: %s -->', esc_html($pathRelativeToThemeFolder));
        }
    }

    $path = $pathIsAbsolute ? $pathRelativeToThemeFolder : Path::join(VENDI_CUSTOM_THEME_PATH, $pathRelativeToThemeFolder);
    if ( ! str_ends_with($path, '.svg')) {
        $path .= '.svg';
    }

    static $svgCache = [];

    $thisSvg = null;

    if ( ! $cache || ! array_key_exists($path, $svgCache)) {
        if ( ! file_exists($path) || ! is_readable($path)) {
            if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY) {
                throw new RuntimeException("File does not exist: $path");
            }
        } else {
            $thisSvg = file_get_contents($path);
        }

        if ($cache) {
            $svgCache[$path] = $thisSvg;
        }
    } else {
        $thisSvg = $svgCache[$path];
    }

    if ($echo) {
        echo $thisSvg;
    }

    return $thisSvg;
}

function vendi_get_env(string $key, ?string $default = null): ?string
{
    return getenv($key) ?: $default;
}

#[\JetBrains\PhpStorm\Deprecated('Use the BaseComponent version')]
function vendi_maybe_get_row_id_attribute(mixed $row_id, bool $echo = true, bool $id_only_no_attribute = false): ?string
{
    if ( ! is_string($row_id) || empty($row_id)) {
        return null;
    }

    if ($id_only_no_attribute) {
        $ret = esc_attr($row_id);
    } else {
        $ret = sprintf(' id="%s"', esc_attr($row_id));
    }

    if ($echo) {
        echo $ret;
    }

    return $ret;
}

function vendi_constrain_item_to_enum_cases(int|bool|null|string $item, array $options, $default = null): null|int|string
{
    $options = array_column($options, 'value');

    return vendi_constrain_item_to_list($item, $options, $default);
}

function vendi_constrain_item_to_list(int|bool|null|string $item, array $options, $default = null): null|int|string
{
    if (in_array($item, $options, true)) {
        return $item;
    }

    return $default;
}

function vendi_get_global_javascript(?string $location): array
{
    static $global_javascript = null;

    if (null === $global_javascript) {
        $global_javascript = get_field('global_javascript', 'options');
    }

    if ( ! $global_javascript || ! is_array($global_javascript)) {
        // Ensure we don't look it update again
        $global_javascript = [];

        return [];
    }

    $ret = [];

    foreach ($global_javascript as $item) {
        if (isset($item['global_javascript_code_block']) && $location === ($item['global_javascript_location'])) {
            $ret[] = $item['global_javascript_code_block'];
        }
    }

    return $ret;
}
