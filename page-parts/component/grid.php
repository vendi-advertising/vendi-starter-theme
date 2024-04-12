<?php

$columns = vendi_get_component_settings('columns');
$column_gap = vendi_get_component_settings('column_gap');
$justify_items = vendi_get_component_settings('justify_items');
$row_gap = vendi_get_component_settings('row_gap');

$columns = match ($columns) {
    'two-columns' => 'grid-col-2',
    'three-columns' => 'grid-col-3',
    'four-columns' => 'grid-col-4',
    'twelve-columns' => 'grid-col-12',
    default => 'grid-col-12',
};

global $respectGridColumnWidth;
$respectGridColumnWidth = $columns === 'grid-col-12';

$column_gap = match ($column_gap) {
    'none' => 'col-gap-none',
    'x-small' => 'col-gap-x-small',
    'small' => 'col-gap-small',
    'medium' => 'col-gap-medium',
    'large' => 'col-gap-large',
    'x-large' => 'col-gap-x-large',
    'xx-large' => 'col-gap-xx-large',
    default => 'col-gap-medium',
};

$row_gap = match ($row_gap) {
    'none' => 'row-gap-none',
    'x-small' => 'row-gap-x-small',
    'small' => 'row-gap-small',
    'medium' => 'row-gap-medium',
    'large' => 'row-gap-large',
    'x-large' => 'row-gap-x-large',
    'xx-large' => 'row-gap-xx-large',
    default => 'row-gap-medium',
};

$justify_items = match ($justify_items) {
    'center' => 'justify-items-center',
    'start' => 'justify-items-start',
    'end' => 'justify-items-end',
    'stretch' => 'justify-items-stretch',
    default => null,
};

$gridClasses = [
    'grid',
    'region',
    $columns,
    $column_gap,
    $justify_items,
    $row_gap,
];

?>

<div
    <?php vendi_render_class_attribute($gridClasses, include_grid_settings: false, include_box_control_settings: true); ?>
    <?php vendi_render_css_styles_for_box_control(); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <?php while (have_rows('rows')): ?>
        <?php the_row(); ?>
        <?php while (have_rows('components')): ?>
            <?php the_row(); ?>
            <?php vendi_load_component_component(get_row_layout()); ?>
        <?php endwhile; ?>
    <?php endwhile; ?>
</div>

