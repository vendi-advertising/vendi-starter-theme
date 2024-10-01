<?php

use Vendi\Theme\GenericComponent;

$component = new GenericComponent( 'component-blockquote' );
$component->setAbortRenderFunction(
    static function () {
        return ! get_sub_field( 'copy' );
    }
);

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

?>
    <blockquote>
        <div class="copy">
            <?php echo $component->getSubField( 'copy' ); ?>
        </div>
        <?php if ( $attribution = $component->getSubField( 'attribution' ) ): ?>
            <footer class="attribution">
                <cite><?php echo $attribution; ?></cite>
            </footer>
        <?php endif; ?>
    </blockquote>

<?php

$component->renderComponentWrapperEnd();
