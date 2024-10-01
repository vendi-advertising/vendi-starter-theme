<?php

use Vendi\Theme\GenericComponent;

$component = new GenericComponent( 'component-figure' );
$image     = $component->getSubField( 'image' );

$component->setAbortRenderFunction(
    static function () use ( $image ) {
        return ! $image || ! is_array( $image );
    }
);

$caption      = $component->getSubField( 'caption' );
$photo_credit = $component->getSubField( 'photo_credit' );


if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

?>

    <div class="region">
        <figure>
            <?php echo wp_get_attachment_image( $image['ID'], 'full' ); ?>
            <?php if ( $caption || $photo_credit ) : ?>
                <figcaption>
                    <?php if ( $caption ): ?>
                        <p class="caption"><?php esc_html_e( $caption ); ?></p>
                    <?php endif; ?>
                    <?php if ( $photo_credit ): ?>
                        <p class="photo-credit"><strong>Photo credit:</strong> <?php esc_html_e( $photo_credit ); ?></p>
                    <?php endif; ?>
                </figcaption>
            <?php endif; ?>
        </figure>
    </div>

<?php

$component->renderComponentWrapperEnd();
