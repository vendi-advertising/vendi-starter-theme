<?php

$columns = null;
$gap = null;
$justify_items = null;
if (have_settings()) {
    // The while loop is needed until we can figure out how to reset
    // the ACF loop. Otherwise, we can't get to get_sub_field later.
    while (have_settings()) {
        the_setting();
        $columns = get_sub_field('columns');
        $gap = get_sub_field('column_gap');
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

$gap = match ($gap) {
    'none' => 'col-gap-none',
    'x-small' => 'col-gap-x-small',
    'small' => 'col-gap-small',
    'medium' => 'col-gap-medium',
    'large' => 'col-gap-large',
    'x-large' => 'col-gap-x-large',
    'xx-large' => 'col-gap-xx-large',
    default => 'col-gap-medium',
};

$justify_items = match ($justify_items) {
    'center' => 'justify-items-center',
    'start' => 'justify-items-start',
    'end' => 'justify-items-end',
    'stretch' => 'justify-items-stretch',
    default => null,
};

$gridClass = [
    'grid',
    $columns,
    $gap,
    $justify_items,
];

?>

<div class="<?php esc_attr_e(implode(' ', $gridClass)); ?>">
    <?php while (have_rows('rows')): ?>
        <?php the_row(); ?>
        <?php while (have_rows('components')): ?>
            <?php the_row(); ?>
            <?php vendi_load_component_component(get_row_layout()); ?>
        <?php endwhile; ?>
    <?php endwhile; ?>
</div>

