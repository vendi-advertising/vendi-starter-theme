<?php
if ((!$testimonial = get_sub_field('testimonial')[0] ?? null) || !$testimonial instanceof WP_Post) {
    return;
}

if (!$copy = get_field('copy', $testimonial->ID)) {
    return;
}
?>
<section class="component-testimonial">
    <div class="region">
        <blockquote>
            <div class="copy">
                <?php echo $copy; ?>
            </div>
            <?php if ($attribution = get_field('attribution', $testimonial->ID)): ?>
                <footer class="attribution h2">
                    <?php echo $attribution; ?>
                </footer>
            <?php endif; ?>
        </blockquote>
        <?php if ($thumbnail = get_the_post_thumbnail($testimonial->ID, 'medium')): ?>
            <div class="thumbnail">
                <?php echo $thumbnail; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
