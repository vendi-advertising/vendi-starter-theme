<?php

use Symfony\Component\Filesystem\Path;

function vendi_unparse_url($parsed_url): string
{

    $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'].'://' : '';
    $host = $parsed_url['host'] ?? '';
    $port = isset($parsed_url['port']) ? ':'.$parsed_url['port'] : '';
    $user = $parsed_url['user'] ?? '';
    $pass = isset($parsed_url['pass']) ? ':'.$parsed_url['pass'] : '';
    $pass = ($user || $pass) ? "$pass@" : '';
    $path = $parsed_url['path'] ?? '';
    $query = isset($parsed_url['query']) ? '?'.$parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';

    return "$scheme$user$pass$host$port$path$query$fragment";
}


function vendi_get_svg(string $pathRelativeToThemeFolder, bool $echo = true, bool $cache = true, ?bool $includeSourcePath = null): ?string
{
    if ($echo) {
        $includeSourcePath = is_null($includeSourcePath) ? defined('WP_DEBUG') && WP_DEBUG : $includeSourcePath;
        if ($includeSourcePath) {
            echo sprintf('<!-- SVG: %s -->', esc_html($pathRelativeToThemeFolder));
        }
    }

    $path = Path::join(VENDI_CUSTOM_THEME_PATH, $pathRelativeToThemeFolder);

    static $svgCache = [];

    $thisSvg = null;

    if (!$cache || !array_key_exists($path, $svgCache)) {
        if (!file_exists($path) || !is_readable($path)) {
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

function vendi_maybe_get_grid_classes(): array
{
    $classes = [];
    $column_span = null;
    if (have_settings()) {
        // The while loop is needed until we can figure out how to reset
        // the ACF loop. Otherwise, we can't get to get_sub_field later.
        while (have_settings()) {
            the_setting();
            $column_span = get_sub_field('column_span');
        }
    }

    $column_span = match ($column_span) {
        '1' => 'grid-col-span-1',
        '2' => 'grid-col-span-2',
        '3' => 'grid-col-span-3',
        '4' => 'grid-col-span-4',
        '5' => 'grid-col-span-5',
        '6' => 'grid-col-span-6',
        '7' => 'grid-col-span-7',
        '8' => 'grid-col-span-8',
        '9' => 'grid-col-span-9',
        '10' => 'grid-col-span-10',
        '11' => 'grid-col-span-11',
        '12' => 'grid-col-span-12',
        default => null,
    };

    $classes[] = $column_span;

    return $classes;
}
