<?php

$header = get_field('cookie_banner_heading', 'options');
$copy   = get_field('cookie_banner_copy', 'options');

if ( ! $header && ! $copy) {
    return;
}

if ( ! function_exists('vendi_get_button_component_classes')) {
    /*
                    <?php  ?>
     */
    function vendi_get_button_component_classes(string $buttonKind, string $buttonStyle, string $buttonColor = 'dark'): array
    {
        return [
            'call-to-action',
            'call-to-action-button',
            $buttonKind,
            $buttonStyle,
            $buttonColor,
        ];
    }
}
?>
<div class="cookie-banner-container hidden" data-role="cookieBanner" role="region"
     <?php if($header): ?>
        aria-labelledby="cookie-banner-header"
     <?php else: ?>
        aria-label="Cookie Banner"
    <?php endif; ?>
>
    <div class="cookie-banner-wrapper region wide">
        <div class="cookie-banner-text">
            <?php if($header): ?>
                <h2 id="cookie-banner-header"><?php esc_html_e($header); ?></h2>
            <?php endif; ?>

            <?php if ($copy): ?>
                <?php echo wp_kses_post($copy); ?>
            <?php endif; ?>
        </div>
        <div class="cookie-banner-button">
            <button class="call-to-action call-to-action-button blue-on-white" data-role="acceptCookie">
                <?php esc_html_e(get_field('cookie_banner_dismiss_button_text', 'options')); ?>
            </button>
        </div>
    </div>
</div>
