<?php

use Vendi\Theme\Component\ImageGallery;

$component = new ImageGallery();

$image_lightbox = 'enabled' === vendi_get_sub_field_constrained_to_list( 'image_lightbox', [ 'enabled', 'disabled' ], 'enabled' );

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}
?>

<?php vendi_render_headline( 'header' ); ?>
<?php if ( ( $images = get_sub_field( 'images' ) ) && ( is_iterable( $images ) ) ): ?>
    <ul>
        <?php foreach ( $images as $image ): ?>
            <?php $full_image_array = bis_get_attachment_image_src( $image['ID'], 'full' ); ?>
            <li>
                <figure>
                    <?php if ( $image_lightbox && ( $full_image_array['src'] ?? null ) ): ?>
                        <a href="<?php echo esc_url( $full_image_array['src'] ); ?>">
                            <?php echo bis_get_attachment_image( $image['ID'], [ 300, 300 ], 1 ); ?>
                        </a>
                    <?php else: ?>
                        <?php echo bis_get_attachment_image( $image['ID'], [ 300, 300 ], 1 ); ?>
                    <?php endif; ?>


                    <?php
                    $title   = null;
                    $caption = null;
                    if ( $component->getImageCaptions() === 'title' || $component->getImageCaptions() === 'title-and-caption' ) {
                        $title = get_the_title( $image['ID'] );
                    }
                    if ( $component->getImageCaptions() === 'caption' || $component->getImageCaptions() === 'title-and-caption' ) {
                        $caption = wp_get_attachment_caption( $image['ID'] );
                    }
                    ?>
                    <?php if ( $title || $caption ): ?>
                        <figcaption>
                            <div class="fig-caption-wrap">
                                <?php if ( $title ): ?>
                                    <strong><?php esc_html_e( $title ); ?></strong>
                                <?php endif; ?>
                                <?php if ( $caption ): ?>
                                    <p><?php esc_html_e( $caption ); ?></p>
                                <?php endif; ?>
                            </div>
                        </figcaption>
                    <?php endif; ?>
                </figure>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php echo get_sub_field( 'copy' ); ?>
<?php
$component->renderComponentWrapperEnd();

if ( ! $image_lightbox ) {
    return;
}

// Ensures that the in-page JS is loaded once, just in case this component is
// used multiple times on the same page.
static $lightbox_js_loaded = false;
if ( $lightbox_js_loaded ) {
    return;
}

vendi_enqueue_baguetteBox();
$lightbox_js_loaded = true;
?>

<script>
    /* global window */
    (function (w) {
        'use strict'

        const
            document = w.document,

            run = () => {
                if (!w.baguetteBox) {
                    w.console.error('baguetteBox is not loaded... exiting');
                    return;
                }

                w
                    .baguetteBox
                    .run(
                        '.component-image-gallery ul',
                        {
                            captions: (element) => {
                                const parentFigure = element.closest('figure');
                                if (parentFigure) {
                                    const caption = parentFigure.querySelector('figcaption');
                                    if (caption) {
                                        return caption.innerHTML;
                                    }
                                }

                                return '';
                            },
                            async: true
                        }
                    )
                ;
            },

            init = () => {
                if (['complete', 'interactive'].includes(document.readyState)) {
                    run();
                } else {
                    document.addEventListener('DOMContentLoaded', run);
                }
            };
        init();
    })(window)
</script>
