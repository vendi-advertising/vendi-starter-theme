<?php
if (!($image = get_sub_field('image')) || !is_array($image)) {
    return;
}

$caption = get_sub_field('caption');
$photo_credit = get_sub_field('photo_credit');
?>
<div
    class="component-figure"
    <?php vendi_maybe_get_row_id_attribute_from_subfield() ?>
>
    <div class="region">
        <figure>
            <?php echo wp_get_attachment_image($image['ID'], 'full'); ?>
            <?php if ($caption || $photo_credit) : ?>
                <figcaption>
                    <?php if ($caption): ?>
                        <p class="caption"><?php esc_html_e($caption); ?></p>
                    <?php endif; ?>
                    <?php if ($photo_credit): ?>
                        <p class="photo-credit"><strong>Photo credit:</strong> <?php esc_html_e($photo_credit); ?></p>
                    <?php endif; ?>
                </figcaption>
            <?php endif; ?>
        </figure>
    </div>
</div>
