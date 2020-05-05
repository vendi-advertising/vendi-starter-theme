<?php

// PhpStorm
assert(function_exists('get_field'));
assert(function_exists('fly_get_attachment_image_src'));
assert(function_exists('fly_get_attachment_image'));

$bannerImage = get_field('banner_image');
$bannerMainImage = get_field('banner_main_image');
$bannerTitle = get_field('banner_title');
$bannerNotation = get_field('banner_photo_notation');

$heroParallaxBackgroundImageSrc = null;
$heroParallaxBackgroundImage = fly_get_attachment_image_src($bannerImage, 'hero-parallax-background');
if ($heroParallaxBackgroundImage && array_key_exists('src', $heroParallaxBackgroundImage)) {
    $heroParallaxBackgroundImageSrc = $heroParallaxBackgroundImage['src'];
}

?>
<div id="hero-banner" class="hero-banner-image" data-role="js-parallax-window">
    <?php if ($heroParallaxBackgroundImageSrc) : ?>
        <div
            data-role="js-parallax-background"
            class="parallax-hero"
            style="background-image: url(<?php echo esc_url($heroParallaxBackgroundImageSrc); ?>); background-size: cover;">
        </div>
    <?php endif; ?>

    <?php if ($bannerMainImage): ?>
        <div class="banner-main-image">
            <?php
            // NOTE: This function escapes properly
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo fly_get_attachment_image($bannerMainImage, 'hero-parallax-foreground');
            ?>
        </div>
    <?php endif; ?>
    <?php if ($bannerTitle): ?>
        <h1 class="banner-title">
            <?php esc_html_e($bannerTitle); ?>
        </h1>
    <?php endif; ?>
    <?php if ($bannerNotation): ?>
        <div class="banner-notation">
            <?php esc_html_e($bannerNotation); ?>
        </div>
    <?php endif; ?>
</div>
