<?php

//global $vendi_component_object_state;
//
//if (!($accordion_items = $vendi_component_object_state['accordion_items'] ?? null)) {
//    return;
//}

// The expand all/collapse all button needs to know the IDs of all the accordions
// for accessibility reasons.
//$allAccordionIds = array_column($accordion_items, 'component_row_id');
?>
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
