<?php

use Vendi\Theme\ComponentStyles;

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
    $params = array(
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

    );

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

function vendi_get_background_settings(bool $renderErrorMessagesForMissingValues = true, ComponentStyles $previousStyles = null, $key = 'backgrounds'): ComponentStyles
{
    $style = $previousStyles ?? new ComponentStyles();
    if (have_rows($key)) {
        while (have_rows($key)) {
            the_row();

            _vendi_get_background_settings_handle_layouts($renderErrorMessagesForMissingValues, $style);
        }
    }

    return $style;
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

function vendi_maybe_render_background_video(): void
{
    if ($backgroundVideo = vendi_get_background_video_iframe()) : ?>
        <div class="background-video">
            <?php echo $backgroundVideo; ?>
        </div>
    <?php endif;
}
