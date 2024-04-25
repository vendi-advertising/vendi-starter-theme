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

function vendi_get_component_settings(string $name, mixed $default_value = null): mixed
{
    // If there is a way to do this without loop, I haven't found it yet.
    if (have_settings()) {
        while (have_settings()) {
            the_setting();
            if ($value = get_sub_field($name)) {

                // This needs more testing, but I believe this is the correct
                // way to "finish" the loop early.
                acf_remove_loop();

                return $value;
            }
        }
    }

    return $default_value;
}

function vendi_maybe_get_row_id_attribute(mixed $row_id, bool $echo = true, bool $id_only_no_attribute = false): ?string
{
    if (!is_string($row_id) || empty($row_id)) {
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

function vendi_render_row_id_attribute(string $sub_field_key = 'component_row_id', bool $echo = true, bool $id_only_no_attribute = false): ?string
{
    return vendi_maybe_get_row_id_attribute(get_sub_field($sub_field_key), $echo, $id_only_no_attribute);
}

function vendi_generate_unique_id(string $component_or_item_id = 'id__'): string
{
    return str_replace('.', '-', uniqid('id__'.$component_or_item_id.'__', true));
}

function vendi_constrain_item_to_list(int|bool|null|string $item, array $options, $default = null): null|int|string
{
    if (in_array($item, $options, true)) {
        return $item;
    }

    return $default;
}

function vendi_constrain_h1_through_h6(null|bool|string $tag, $default = 'h2'): string
{
    return vendi_constrain_item_to_list($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], $default);
}

function vendi_get_global_javascript(?string $location): array
{
    static $global_javascript = null;

    if (null === $global_javascript) {
        $global_javascript = get_field('global_javascript', 'options');
    }

    if (!$global_javascript || !is_array($global_javascript)) {
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

function vendi_render_attribute(string $name, null|bool|string $value): void
{
    if (true === $value) {
        echo sprintf('%s', esc_attr($name));

        return;
    }

    if (!$value) {
        return;
    }

    echo sprintf('%s="%s"', esc_attr($name), esc_attr($value));
}

function vendi_render_class_attribute(array|string $classes, bool $include_grid_settings = true, bool $include_box_control_settings = false): void
{
    if (is_string($classes)) {
        $classes = explode(' ', $classes);
    }

    if ($include_grid_settings) {
        $classes = array_merge($classes, vendi_maybe_get_grid_classes());
    }

    if ($include_box_control_settings) {
        $classes = array_merge($classes, vendi_get_css_classes_for_box_control());
    }

    if (!$classes = array_filter($classes)) {
        return;
    }
    echo 'class="'.esc_attr(implode(' ', $classes)).'"';
}

function vendi_convert_alerts_to_objects($alerts): array
{
    $ret = [];
    foreach ($alerts as $alert) {
        $obj = new stdClass();
        $obj->id = $alert->ID;
        $obj->headline = get_field('headline', $alert->ID);
        $obj->alert_version = get_field('alert_version', $alert->ID);
        $obj->start_date = get_field('start_date', $alert->ID);
        $obj->end_date = get_field('end_date', $alert->ID);
        $obj->alert_type = get_field('alert_type', $alert->ID);
        $obj->display_mode = get_field('display_mode', $alert->ID);
        $obj->primary_message = get_field('primary_message', $alert->ID);
        $obj->alert_style = get_field('alert_style', $alert->ID);
        $obj->background_color = get_field('background_color', $alert->ID);
        $obj->icon = get_field('icon', $alert->ID);
        $obj->priority = get_field('alert_priority', $alert->ID);

        $ret[] = $obj;
    }

    return $ret;
}

function vendi_render_headline(string $sub_field_headline, string $sub_field_for_heading_level = 'heading_level', string $sub_field_for_include_in_document_outline = 'include_in_document_outline'): void
{
    if (!$headline = get_field($sub_field_headline)) {
        return;
    }

    $headline_level = vendi_constrain_h1_through_h6(get_field($sub_field_for_heading_level));
    $headline_tag = 'no' === get_field($sub_field_for_include_in_document_outline) ? 'div' : $headline_level;

    echo sprintf('<%1$s class="%2$s">%3$s</%1$s>', $headline_tag, $headline_level, esc_html($headline));
}
