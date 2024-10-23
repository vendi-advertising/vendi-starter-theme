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

$image_max_height = null;
if ($image_max_height = get_sub_field('image_max_height')) {
    $image_max_height_unit = get_sub_field('image_max_height_unit');
}

$gridClasses = [
    'component-image-grid',
    'grid',
    'region',
    $columns,
    $column_gap,
    $justify_items,
    $row_gap,
];
?>
<ul
    <?php vendi_render_class_attribute($gridClasses) ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <?php while (have_rows('images')): ?>
        <?php the_row(); ?>
        <li class="grid-cell">
            <?php if (!$image = get_sub_field('image')): ?>
                <?php continue; ?>
            <?php endif; ?>
            <?php if ($link = get_sub_field('link')): ?>
            <a href="<?php echo esc_url($link['url']); ?>" target="<?php esc_attr_e($link['target']); ?>">
                <?php endif; ?>
                <img
                    src="<?php echo esc_url($image['url']); ?>"
                    alt="<?php echo esc_attr($image['alt']); ?>"

                    <?php if ($title = $image['title']): ?>
                        title="<?php echo esc_attr($title); ?>"
                    <?php endif; ?>
                />
                <?php if ($link): ?>
            </a>
        <?php endif; ?>
        </li>
    <?php endwhile; ?>
</ul>
