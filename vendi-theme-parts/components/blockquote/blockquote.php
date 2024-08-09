<?php
if (!$copy = get_sub_field('copy')) {
    return;
}

// NOTE: This component is intentionally a div, not a section, because its place in the document outline
// is not clear.

// NOTE: This the blockquote and testimonial components are almost identical, with the only difference being
// that the latter is a CPT that also supports an optional image.
?>
<div
    <?php vendi_render_class_attribute('component-blockquote'); ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <blockquote>
            <div class="copy">
                <?php echo $copy; ?>
            </div>
            <?php if ($attribution = get_sub_field('attribution')): ?>
                <footer class="attribution">
                    <cite><?php echo $attribution; ?></cite>
                </footer>
            <?php endif; ?>
        </blockquote>
    </div>
</div>
