<?php

global $vendi_component_object_state;

if (!($accordion_items = $vendi_component_object_state['accordion_items'] ?? null)) {
    return;
}

// TODO: This does not take into account different layouts yet
?>

<?php foreach ($accordion_items as $accordion_item): ?>
    <?php $display_mode = vendi_constrain_item_to_list($accordion_item['display_mode'], ['always-open', 'closed', 'open'], 'closed'); ?>
    <details
        class="single-accordion-item"
        <?php echo in_array($display_mode, ['always-open', 'open'], true) ? 'open' : ''; ?>
        <?php echo $display_mode === 'always-open' ? 'data-always-open' : ''; ?>
        <?php vendi_maybe_get_row_id_attribute($accordion_item['component_row_id']); ?>
    >
        <summary>

            <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>

            <div>
                <?php esc_html_e($accordion_item['title']); ?>
            </div>
        </summary>
        <div class="copy">
            <?php echo $accordion_item['copy']; ?>
        </div>
    </details>
<?php endforeach; ?>
