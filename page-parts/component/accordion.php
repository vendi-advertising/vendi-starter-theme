<?php
if ((!$accordion_items = get_sub_field('accordion_items')) || !is_array($accordion_items) || !count($accordion_items)) {
    return;
}

$expand_collapse_all = vendi_constraint_item_to_list(get_sub_field('expand_collapse_all'), ['hide', 'show'], 'hide');

foreach ($accordion_items as &$accordion_item) {
    if (empty($accordion_item['component_row_id'])) {
        $accordion_item['component_row_id'] = vendi_generate_unique_id('accordion-item');
    }
}
unset($accordion_item);

// The expand all/collapse all button needs to know the IDs of all the accordions
// for accessibility reasons.
$allAccordionIds = array_column($accordion_items, 'component_row_id');

?>
<section
    class="component-accordion"
    data-role="accordion"
    <?php if ('show' === get_sub_field('expand_collapse_all')): ?>
        data-expand-collapse-available
    <?php endif; ?>
    <?php vendi_maybe_get_row_id_attribute_from_subfield() ?>
>
    <div class="region">

        <?php if ($intro_headline = get_sub_field('intro_headline')): ?>
            <?php $intro_headline_level = vendi_constraint_h1_through_h6(get_sub_field('intro_headline_level'), 'h2'); ?>
            <?php echo sprintf('<%1$s>', $intro_headline_level); ?>
            <?php esc_html_e($intro_headline); ?>
            <?php echo sprintf('</%1$s>', $intro_headline_level); ?>
        <?php endif; ?>

        <ul class="accordion-controls list-as-nav" aria-label="section controls" data-role="accordion-controls">
            <li>
                <button
                    aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
                    class="expand-all"
                    data-action="expand-all"
                >
                    <span>Expand all</span>
                </button>
            </li>
            <li>
                <button
                    aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
                    class="collapse-all"
                    data-action="collapse-all"
                >
                    <span>Collapse all</span>
                </button>
            </li>
        </ul>

        <?php foreach ($accordion_items as $accordion_item): ?>
            <?php $display_mode = vendi_constraint_item_to_list($accordion_item['display_mode'], ['always-open', 'closed', 'open'], 'closed'); ?>
            <details
                <?php echo in_array($display_mode, ['always-open', 'open'], true) ? 'open' : ''; ?>
                <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
                <?php vendi_maybe_get_row_id_attribute($accordion_item['component_row_id']); ?>
            >
                <summary>
                    <?php esc_html_e($accordion_item['title']); ?>
                </summary>
                <div class="copy">
                    <?php echo $accordion_item['copy']; ?>
                </div>
            </details>
        <?php endforeach; ?>
    </div>
</section>

<script>

</script>
