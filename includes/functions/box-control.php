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

function vendi_get_css_stuff_for_box_control(callable $func, string $boxControlKey = 'box_control'): array
{
    $boxControl = vendi_get_component_settings($boxControlKey);

    dump($boxControl);


//    $stuff = [];
//    $stuff = array_merge($stuff, $func($margin['vendi-acf-box-control'] ?? [], 'margin'));
//    $stuff = array_merge($stuff, $func($padding['vendi-acf-box-control'] ?? [], 'padding'));
    $stuff = [];

    return $stuff;
}

function vendi_get_css_styles_for_box_control(string $boxControlKey = 'box_control'): array
{
    return vendi_get_css_stuff_for_box_control('vendi_get_css_styles_for_box_control_single', $boxControlKey);
}

function vendi_get_css_classes_for_box_control(string $boxControlKey = 'box_control'): array
{
    return vendi_get_css_stuff_for_box_control('vendi_get_css_classes_for_box_control_single', $boxControlKey);
}

function vendi_render_css_styles_for_box_control(string $boxControlKey = 'box_control'): void
{
    vendi_render_css_styles(vendi_get_css_styles_for_box_control($boxControlKey));
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
