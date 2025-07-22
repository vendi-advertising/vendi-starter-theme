<?php

$heading = null;

if (defined('VENDI_THEME_CUSTOM_SEARCH')) {
    $heading = 'Search Results';
}

if ( ! $heading && 'simple' === get_row_layout()) {
    $heading = get_sub_field('heading');
}

if ( ! $heading) {
    $heading = get_the_title();
}
?>
<section class="hero hero-simple">
    <?php vendi_load_component_v3('mantle'); ?>
    <h1 class="heading"><?php esc_html_e($heading); ?></h1>

    <?php
    if ( ! is_404() && ! is_front_page()) {
        vendi_load_component_v3('breadcrumbs');
    }
    ?>
</section>
