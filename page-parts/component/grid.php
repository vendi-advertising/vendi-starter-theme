<?php

$columns = null;
$column_gap = null;
$justify_items = null;
$row_gap = null;
if (have_settings()) {
    // The while loop is needed until we can figure out how to reset
    // the ACF loop. Otherwise, we can't get to get_sub_field later.
    while (have_settings()) {
        the_setting();
        $columns = get_sub_field('columns');
        $column_gap = get_sub_field('column_gap');
        $row_gap = get_sub_field('row_gap');
        $justify_items = get_sub_field('justify_items');
    }
}

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

$gridClasses = array_merge($gridClasses, vendi_get_css_classes_for_box_control());
$styles = vendi_get_css_styles_for_box_control();
?>

<div
    <?php vendi_render_class_attribute($gridClasses, include_grid_settings: false); ?>
    <?php vendi_render_css_styles($styles); ?>
>
    <?php while (have_rows('rows')): ?>
        <?php the_row(); ?>
        <?php while (have_rows('components')): ?>
            <?php the_row(); ?>
            <?php vendi_load_component_component(get_row_layout()); ?>
        <?php endwhile; ?>
    <?php endwhile; ?>
</div>

