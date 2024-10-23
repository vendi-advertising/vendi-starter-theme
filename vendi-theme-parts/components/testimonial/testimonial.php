<?php

use Vendi\Theme\GenericComponent;

$component   = new GenericComponent( 'component-testimonial' );
$testimonial = $component->getSubField( 'testimonial' )[0] ?? null;
$copy        = get_field( 'copy', $testimonial?->ID );
$component->setAbortRenderFunction(
    static function () use ( $testimonial, $copy ) {
        return ( ! $testimonial instanceof WP_Post ) || ( ! $copy );
    }
);

$component->componentStyles->addStyle( '--local-text-color', $component->getSubField( 'text_color' ) );

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}
?>


    <blockquote class="blockquote">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129.657 103.134">
            <defs>
                <linearGradient id="testimonial_linear_gradient_a" x1=".5" x2=".5" y2="1" gradientUnits="objectBoundingBox">
                    <stop offset="0" stop-color="#406bd9"/>
                    <stop offset="1" stop-color="#002e73"/>
                </linearGradient>
                <clipPath id="testimonial_linear_gradient_b">
                    <path fill="url(#testimonial_linear_gradient_a)" d="M0 0h129.657v103.134H0z"/>
                </clipPath>
            </defs>
            <g clip-path="url(#testimonial_linear_gradient_b)">
                <path fill="url(#testimonial_linear_gradient_a)" d="M51.768 0v8.2q-17.593 9.192-25.185 19.184a35.235 35.235 0 0 0-7.594 21.786q0 7 2 9.595 1.8 2.8 4.4 2.8a23.185 23.185 0 0 0 6.994-1.5 25.014 25.014 0 0 1 8-1.5 19.581 19.581 0 0 1 14.291 6.1 20.266 20.266 0 0 1 6.1 14.89 21.782 21.782 0 0 1-7.407 16.485q-7.4 6.9-18.388 6.9-13.393 0-24.185-11.591T0 62.762q0-19.984 13.291-37.277T51.768 0m68.3.6v7.6Q99.874 19.789 93.481 28.984a36.839 36.839 0 0 0-6.4 21.586q0 5.6 2.2 8.393t4.6 2.8a21.554 21.554 0 0 0 6.6-1.6 25.588 25.588 0 0 1 8.794-1.6 19.909 19.909 0 0 1 14.291 5.9 19.38 19.38 0 0 1 6.1 14.49q0 9.795-7.695 16.988a26.622 26.622 0 0 1-18.887 7.2q-13.192 0-23.786-11.393T68.7 63.361q0-20.988 13.391-38.276T120.064.6"/>
            </g>
        </svg>
        <?php if ( $header = $component->getSubField( 'header' ) ): ?>
            <h2 class="header-with-dots"><?php esc_html_e( $header ); ?></h2>
        <?php endif; ?>
        <h3 class="title"><?php esc_html_e( get_the_title( $testimonial ) ); ?></h3>
        <div class="copy">
            <?php echo wp_kses_post( $copy ); ?>
        </div>
        <?php if ( $attribution = get_field( 'attribution', $testimonial->ID ) ): ?>
            <footer class="attribution h2">
                <cite><?php echo $attribution; ?></cite>
            </footer>
        <?php endif; ?>
    </blockquote>
<?php if ( $thumbnail = get_the_post_thumbnail( $testimonial->ID, 'medium' ) ): ?>
    <div class="thumbnail">
        <?php echo $thumbnail; ?>
    </div>
<?php endif; ?>

<?php

$component->renderComponentWrapperEnd();
