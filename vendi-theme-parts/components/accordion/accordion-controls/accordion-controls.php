<?php

use Vendi\Theme\Component\Accordion;

global $vendi_component_object_state;

if ( ! $component = $vendi_component_object_state['component'] ?? null) {
    return;
}

if ( ! $component instanceof Accordion) {
    return;
}

if ('show' !== get_sub_field('expand_collapse_all')) {
    return;
}

$accordion_items = $component->getAccordionItems();

$expandCollapseAllButtonStyle = '' . ($vendi_component_object_state['expandCollapseAllButtonStyle'] ?? null);

// The expand all/collapse all button needs to know the IDs of all the accordions
// for accessibility reasons.
$allAccordionIds = array_column($accordion_items, 'component_row_id');
?>
<ul class="accordion-controls list-as-nav" aria-label="section controls" data-role="accordion-controls">
    <li>
        <button
            aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
            <?php if ($expandCollapseAllButtonStyle): ?>
                vendi-button-style="<?php esc_attr_e($expandCollapseAllButtonStyle); ?>"
            <?php endif; ?>
            class="expand-all call-to-action call-to-action-button"
            data-action="expand-all"
        >
            <span>Expand all</span>
        </button>
    </li>
    <li>
        <button
            aria-controls="<?php esc_attr_e(implode(' ', $allAccordionIds)); ?>"
            <?php if ($expandCollapseAllButtonStyle): ?>
                vendi-button-style="<?php esc_attr_e($expandCollapseAllButtonStyle); ?>"
            <?php endif; ?>
            class="collapse-all call-to-action call-to-action-button"
            data-action="collapse-all"
        >
            <span>Collapse all</span>
        </button>
    </li>
</ul>
