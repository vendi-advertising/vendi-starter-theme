<?php

use Vendi\ThemeParts\Component\Columns;

$component = new Columns();

// We have to limit and guard this early
$number_of_columns = $component->getNumberOfColumns();

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>

<?php vendi_render_heading($component); ?>

<?php for ($i = 1; $i <= $number_of_columns; $i++): ?>
    <?php $prefix = sprintf('column_%1$d_', $i); ?>
    <?php $justify_self = $component->getSubField($prefix . 'settings_justify_self'); ?>
    <?php $align_self = $component->getSubField($prefix . 'settings_align_self'); ?>
    <div
        class="column"
        style="--local-justify-self: <?php echo esc_attr($justify_self); ?>; --local-align-self: <?php echo esc_attr($align_self); ?>;"
    >
        <?php $selector = $prefix . 'column_content'; ?>
        <?php if (have_rows($selector)): ?>
            <?php while (have_rows($selector)) : the_row(); ?>
                <?php vendi_load_component_v3(get_row_layout()); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
<?php endfor; ?>

<?php

$component->renderComponentWrapperEnd();
