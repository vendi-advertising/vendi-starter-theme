<section
    <?php vendi_render_class_attribute(['component-stepwise']) ?>
    <?php vendi_render_row_id_attribute() ?>
>
    <div class="region">
        <?php if (have_rows('steps')): ?>
            <ol class="steps">
                <?php while (have_rows('steps')): ?>
                    <?php the_row(); ?>
                    <li class="step">
                        <h2 class="heading"><?php esc_html_e(get_sub_field('heading')); ?></h2>
                        <div class="copy">
                            <?php echo wp_kses_post(get_sub_field('copy')); ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ol>
        <?php endif; ?>
    </div>
</section>
