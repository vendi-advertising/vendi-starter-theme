<?php

function vendi_maybe_get_grid_classes(): array
{
    $classes = [];
    $column_span = vendi_get_component_settings('column_span');

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
