<?php

function vendi_get_css_styles_for_box_control_single(array $settings, string $prefix): array
{
    $styles = [];
    foreach (['block', 'inline'] as $side) {
        $preset = $settings[$side]['preset'] ?? null;

        if ('custom' === $preset) {
            foreach (['start', 'end'] as $direction) {
                $value = $settings[$side][$direction]['value'] ?? null;
                $unit = $settings[$side][$direction]['unit'] ?? null;
                if ($value && $unit) {
                    $styles[] = sprintf('%s-%s-%s: %s%s;', $prefix, $side, $direction, $value, $unit);
                }
            }
        }
    }

    return array_filter($styles);
}

function vendi_get_css_classes_for_box_control_single(array $settings, string $prefix): array
{
    $classes = [];
    foreach (['block', 'inline'] as $side) {
        $preset = $settings[$side]['preset'] ?? null;

        if ('custom' === $preset) {
            continue;
        }

        $presetClass = match ($preset) {
            '' => implode('-', [$prefix, $side, 'component-default']),
            default => implode('-', [$prefix, $side, $preset]),
        };
        $classes[] = $presetClass;
    }

    return array_filter($classes);
}

function vendi_get_css_stuff_for_box_control(callable $func, string $marginKey = 'margin', string $paddingKey = 'padding'): array
{
    $margin = null;
    $padding = null;
    if (have_settings()) {
        // The while loop is needed until we can figure out how to reset
        // the ACF loop. Otherwise, we can't get to get_sub_field later.
        while (have_settings()) {
            the_setting();
            $margin = get_sub_field('margin');
            $padding = get_sub_field('padding');
        }
    }

    $stuff = [];
    $stuff = array_merge($stuff, $func($margin['vendi-acf-box-control'] ?? [], 'margin'));
    $stuff = array_merge($stuff, $func($padding['vendi-acf-box-control'] ?? [], 'padding'));

    return $stuff;
}

function vendi_get_css_styles_for_box_control(string $marginKey = 'margin', string $paddingKey = 'padding'): array
{
    return vendi_get_css_stuff_for_box_control('vendi_get_css_styles_for_box_control_single', $marginKey, $paddingKey);
}

function vendi_get_css_classes_for_box_control(string $marginKey = 'margin', string $paddingKey = 'padding'): array
{
    return vendi_get_css_stuff_for_box_control('vendi_get_css_classes_for_box_control_single', $marginKey, $paddingKey);
}

function vendi_render_css_styles(string|array|null $styles): void
{
    if (!$styles) {
        return;
    }

    if (is_string($styles)) {
        $styles = explode(';', $styles);
    }

    if (!$styles = array_filter($styles)) {
        return;
    }
    echo 'style="'.implode('; ', $styles).'"';
}
