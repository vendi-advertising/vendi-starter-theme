<?php
if ((!$testimonial = get_sub_field('testimonial')[0] ?? null) || !$testimonial instanceof WP_Post) {
    return;
}

if (!$copy = get_field('copy', $testimonial->ID)) {
    return;
}

// NOTE: This component is intentionally a div, not a section, because its place in the document outline
// is not clear.

// NOTE: This the blockquote and testimonial components are almost identical, with the only difference being
// that the latter is a CPT that also supports an optional image.
?>
<div
    class="component-testimonial"
    <?php vendi_maybe_get_row_id_attribute_from_subfield() ?>
>
    <div class="region">
        <blockquote>
            <div class="copy">
                <?php echo $copy; ?>
            </div>
            <?php if ($attribution = get_field('attribution', $testimonial->ID)): ?>
                <footer class="attribution h2">
                    <cite><?php echo $attribution; ?></cite>
                </footer>
            <?php endif; ?>
        </blockquote>
        <?php if ($thumbnail = get_the_post_thumbnail($testimonial->ID, 'medium')): ?>
            <div class="thumbnail">
                <?php echo $thumbnail; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
