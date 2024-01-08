<?php
// NOTE: This component is intentionally a div, not a section, because its place in the document outline
// is not clear.
?>
<div
    class="component-callout"
    <?php vendi_maybe_get_row_id_attribute_from_subfield() ?>
>
    <div class="region">
        <?php echo get_sub_field('copy'); ?>
    </div>
</div>
