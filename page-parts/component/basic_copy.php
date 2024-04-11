<?php
$classes = ['component-basic-copy'];

$column_span = null;
if (have_settings()) {
    // The while loop is needed until we can figure out how to reset
    // the ACF loop. Otherwise, we can't get to get_sub_field later.
    while (have_settings()) {
        the_setting();
        $column_span = get_sub_field('column_span');
    }
}

global $respectGridColumnWidth;

if ($respectGridColumnWidth) {
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
} else {
    $column_span = null;
}

$classes[] = $column_span;

?>
<section
    <?php vendi_render_class_attribute($classes); ?>
    <?php vendi_maybe_get_row_id_attribute_from_subfield(); ?>
>
    <div class="region">
        <?php echo get_sub_field('copy') ?>
    </div>
</section>
