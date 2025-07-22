<?php

use Vendi\Theme\Component\ImageHero;
use Vendi\Theme\ComponentUtility;

/** @var ImageHero $component */
$component = ComponentUtility::get_new_component_instance(ImageHero::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>
    <section class="hero hero-image">
        <?php vendi_load_component_v3('mantle'); ?>
        <div class="box">
            <div class="image">
                <?php vendi_get_svg('images/icons/shield') ?>
            </div>
            <div class="content sub-component-basic-copy">
                <?php if ($heading = get_sub_field('heading_same_as_page_title') ? get_the_title() : get_sub_field('heading')): ?>
                    <h1 class="heading">
                        <?php esc_html_e($heading); ?>
                    </h1>
                <?php endif; ?>
                <?php if ($copy = get_sub_field('copy2')): ?>
                    <?php echo wp_kses_post($copy); ?>
                <?php endif; ?>

                <?php $component->maybeRenderCallsToAction(); ?>

            </div>
        </div>

        <?php
        if ( ! is_404() && ! is_front_page()) {
            vendi_load_component_v3('breadcrumbs');
        }
        ?>
    </section>

<?php
$component->renderComponentWrapperEnd();
