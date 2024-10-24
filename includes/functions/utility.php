<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\ComponentStyles;

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
    if (vendi_have_settings()) {
        while (vendi_have_settings()) {
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

function vendi_get_sub_field_boolean(string $sub_field, ?bool $default = null): bool
{
    return 'true' === vendi_constrain_item_to_list(get_sub_field($sub_field), ['true', 'false'], $default);
}

function vendi_get_sub_field_range_int(string $sub_field, int $min, int $max, $default = null): null|int|string
{
    return vendi_get_sub_field_constrained_to_list($sub_field, array_map('strval', range($min, $max)), $default);
}

function vendi_get_sub_field_constrained_to_list(string $sub_field, array $options, $default = null): null|int|string
{
    return vendi_constrain_item_to_list(get_sub_field($sub_field), $options, $default);
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

function vendi_render_class_attribute(array|string $classes): void
{
    if (is_string($classes)) {
        $classes = explode(' ', $classes);
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

function vendi_render_headline(string $sub_field_headline = 'header', string $sub_field_for_heading_level = 'heading_level', string $sub_field_for_include_in_document_outline = 'include_in_document_outline', array|string $additional_css_classes = 'header'): void
{
    if (!$headline = get_sub_field($sub_field_headline)) {
        return;
    }

    $headline_level = vendi_constrain_h1_through_h6(get_sub_field($sub_field_for_heading_level));
    $headline_tag = 'no' === get_sub_field($sub_field_for_include_in_document_outline) ? 'div' : $headline_level;

    if (is_string($additional_css_classes)) {
        $classes = explode(' ', $additional_css_classes);
    } else {
        $classes = $additional_css_classes;
    }

    $classes[] = $headline_level;

    echo sprintf('<%1$s class="%2$s">%3$s</%1$s>', $headline_tag, implode(' ', $classes), esc_html($headline));
}

function vendi_maybe_render_html_comment_error_message(string $errorText, bool $render = true): void
{
    if (defined('WP_DEBUG') && WP_DEBUG && $render) {
        echo sprintf('<!-- %s -->', esc_html($errorText));
    }
}

function vendi_render_component_data_name_and_index_attributes(string $componentName, int $componentIndex): void
{
    $attributes = [
        'data-component-name' => $componentName,
        'data-component-index' => $componentIndex,
    ];

    echo implode(
        ' ',
        array_map(
            static fn($key) => sprintf("%s=\"%s\"", esc_attr($key), esc_attr($attributes[$key])),
            array_keys($attributes),
        ),
    );
}


function vendi_render_component_specific_css_block(string $componentName, int $componentIndex, ComponentStyles $styles): void
{
    ?>
    <style media="screen">
        [data-component-name="<?php esc_attr_e($componentName); ?>"][data-component-index="<?php esc_attr_e($componentIndex); ?>"] {
        <?php echo $styles->__toString(); ?>
        }
    </style>
    <?php
}

function _vendi_get_background_settings_handle_layout_background_color(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
{
    if (!$background_color = get_sub_field('background_color')) {
        vendi_maybe_render_html_comment_error_message('Missing background_color', $renderErrorMessagesForMissingValues);

        return;
    }

    $style->addBackgroundImage("linear-gradient(0deg, {$background_color} 0%, {$background_color} 100%)");
    $style->addStyle('background-blend-mode', vendi_get_background_blend_mode_from_settings(post_id: $post_id));
    $style->addStyle('background-size', vendi_get_background_size_from_settings(post_id: $post_id));
    $style->addStyle('background-repeat', vendi_get_background_repeat_from_settings(post_id: $post_id));
}

function vendi_get_background_video_iframe(bool $renderErrorMessagesForMissingValues = true, string $key = 'backgrounds', ?WP_Post $post_id = null): ?string
{
    $background_video = null;
    if (have_rows($key, $post_id)) {
        while (have_rows($key, $post_id)) {
            the_row();

            if ('background_video' === get_row_layout()) {
                $background_video = get_sub_field('background_video');
            }
        }
    }

    if (!$background_video) {
        vendi_maybe_render_html_comment_error_message('Missing background_video', $renderErrorMessagesForMissingValues);

        return null;
    }

    // Add autoplay functionality to the video code
    if (!preg_match('/src="(?<video>.+?)"/', $background_video, $matches)) {
        vendi_maybe_render_html_comment_error_message('Could not find video URL in background_video', $renderErrorMessagesForMissingValues);
    }

    // Video source URL
    $src = $matches['video'];

    // Add option to hide controls, enable HD, and do autoplay -- depending on provider
    $params = [
        'playsinline' => 1,
        'controls' => 0,
        'hd' => 1,
        'autoplay' => 1,
        'background' => 1,
        'loop' => 1,
        'byline' => 0,
        'title' => 0,
        'muted' => 1,
        'mute' => 1,

    ];

    $new_src = add_query_arg($params, $src);

    $background_video = str_replace($src, $new_src, $background_video);

    // add extra attributes to iframe html
    $attributes = 'frameborder="0" autoplay muted loop playsinline webkit-playsinline';

    return str_replace('></iframe>', ' '.$attributes.'></iframe>', $background_video);
}

function _vendi_get_background_settings_handle_layout_background_image(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
{
    if (!$background_image = get_sub_field('background_image')) {
        vendi_maybe_render_html_comment_error_message('Missing background_image', $renderErrorMessagesForMissingValues);

        return;
    }
    $style->addBackgroundImage("url('{$background_image['url']}')");
    $style->addStyle('background-blend-mode', vendi_get_background_blend_mode_from_settings(post_id: $post_id));
    $style->addStyle('background-size', vendi_get_background_size_from_settings(post_id: $post_id));
    $style->addStyle('background-repeat', vendi_get_background_repeat_from_settings(post_id: $post_id));

    sanitize_focal_point($background_image['ID']);

    if ($focal_point = sanitize_focal_point(get_post_meta($background_image['ID'], 'focal_point', true))) {
        [$left, $top] = $focal_point;
        $left *= 100;
        $top *= 100;
        $style->addStyle('background-position', "{$left}% {$top}%");
    }
}

function _vendi_get_background_settings_handle_layout_linear_gradient(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
{
    $angle = get_sub_field('angle');

    if (!$colors_stops = get_sub_field('colors_stops')) {
        vendi_maybe_render_html_comment_error_message('Missing background_image_overlay_gradient', $renderErrorMessagesForMissingValues);

        return;
    }

    if (!is_array($colors_stops) || count($colors_stops) < 2) {
        vendi_maybe_render_html_comment_error_message('Invalid colors_stops, must have at least two', $renderErrorMessagesForMissingValues);

        return;
    }

    $stopsAndColors = [];
    foreach ($colors_stops as $value) {
        $color = $value['color'] ?? '';
        $stop = $value['stop'] ?? '';
        $stop_unit = $value['stop_unit'] ?? '%';

        $stopsAndColors[] = "{$color} {$stop}{$stop_unit}";
    }

    $style->addBackgroundImage("linear-gradient({$angle}deg, ".implode(', ', $stopsAndColors).")");
    $style->addStyle('background-blend-mode', vendi_get_background_blend_mode_from_settings(post_id: $post_id));
    $style->addStyle('background-size', vendi_get_background_size_from_settings(post_id: $post_id));
    $style->addStyle('background-repeat', vendi_get_background_repeat_from_settings(post_id: $post_id));
}

function _vendi_get_background_settings_handle_layouts(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
{
    switch (get_row_layout()) {
        case 'background_color':
            _vendi_get_background_settings_handle_layout_background_color($renderErrorMessagesForMissingValues, $style, $post_id);

            return;
        case 'background_image':
            _vendi_get_background_settings_handle_layout_background_image($renderErrorMessagesForMissingValues, $style, $post_id);

            return;

        case 'background_video':
//            _vendi_get_background_settings_handle_layout_background_video( $renderErrorMessagesForMissingValues, $style, $post_id );

            return;
        case 'linear_gradient':
            _vendi_get_background_settings_handle_layout_linear_gradient($renderErrorMessagesForMissingValues, $style, $post_id);

            return;

        case 'reusable_background':
            if ((!$backgroundPost = get_sub_field('background')) || (!$backgroundPost instanceof WP_Post)) {
                return;
            }

            if (have_rows('entity_backgrounds', $backgroundPost)) {
                while (have_rows('entity_backgrounds', $backgroundPost)) {
                    the_row();

                    _vendi_get_background_settings_handle_layouts($renderErrorMessagesForMissingValues, $style, $backgroundPost);
                }
            }

            return;
    }
}

function vendi_get_background_settings(ComponentStyles $previousStyles, bool $renderErrorMessagesForMissingValues = true, $key = 'backgrounds'): void
{
    if (have_rows($key)) {
        while (have_rows($key)) {
            the_row();

            _vendi_get_background_settings_handle_layouts($renderErrorMessagesForMissingValues, $previousStyles);
        }
    }
}

function vendi_get_background_blend_mode_from_settings($default_value = 'normal', $post_id = false)
{
    $ret = $default_value;
    if (vendi_have_settings($post_id)) {
        while (vendi_have_settings($post_id)) {
            the_setting();
            if ($blend_mode = get_sub_field('blend_mode')) {
                $ret = $blend_mode;
            }
        }
    }

    return $ret;
}


function vendi_get_background_repeat_from_settings($default_value = 'repeat', $post_id = false)
{
    $ret = $default_value;
    if (vendi_have_settings($post_id)) {
        while (vendi_have_settings($post_id)) {
            the_setting();
            if ($background_repeat = get_sub_field('background_repeat')) {
                $ret = $background_repeat;
            }
        }
    }

    return $ret;
}

function vendi_get_background_size_from_settings($default_value = 'auto', $post_id = false)
{
    $ret = $default_value;
    if (vendi_have_settings($post_id)) {
        while (vendi_have_settings($post_id)) {
            the_setting();
            if ($background_size = get_sub_field('background_size')) {
                $ret = $background_size;
            }
        }
    }

    return $ret;
}

function vendi_have_settings($post_id = false): bool
{
    return have_rows('layout_settings', $post_id);
}

function vendi_maybe_render_background_video(): void
{
    if ($backgroundVideo = vendi_get_background_video_iframe()) : ?>
        <div class="background-video">
            <?php
            echo $backgroundVideo; ?>
        </div>
    <?php
    endif;
}

function vendi_get_common_component_content_settings_classes()
{
    ['classes' => $content_classes] = vendi_get_common_component_settings();

    return $content_classes;
}

function vendi_get_common_component_settings(): array
{
    $ret = ['classes' => []];

    $settingsGroup = get_sub_field('content_area_settings');

    if (!$settingsGroup) {
        return $ret;
    }

    // NOTE: XX-Large is not included in the UI currently but was left here for consistency
    $settings = ['content_max_width', 'content_placement', 'content_vertical_padding', 'content_horizontal_padding'];

    $classes = [];

    foreach ($settings as $setting) {
        if (!$value = $settingsGroup[$setting] ?? null) {
            continue;
        }
        switch ($setting) {
            case 'content_max_width':
                $ret[$setting] = vendi_constrain_item_to_list($value, ['full', 'narrow', 'slim'], 'narrow');
                $classes[] = 'content-max-width-'.$ret[$setting];
                break;
            case 'content_placement':
                $ret[$setting] = vendi_constrain_item_to_list($value, ['left', 'middle'], 'left');
                $classes[] = 'content-placement-'.$ret[$setting];
                break;
            case 'content_vertical_padding':
                $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
                $classes[] = 'content-vertical-padding-'.$ret[$setting];
                break;
            case 'content_horizontal_padding':
                $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
                $classes[] = 'content-horizontal-padding-'.$ret[$setting];
                break;
        }
    }

    $ret['classes'] = $classes;

    return $ret;
}
