<?php

use Vendi\Theme\Component\Accordion;

$component = new Accordion();
if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

?>

<?php vendi_render_headline( 'intro_heading', with_dots: true, additional_css_classes: [ 'header', 'padding-inline-small' ] ); ?>
<?php if ( $additional_copy = $component->getSubFieldAndCache( 'additional_copy' ) ): ?>
    <div class="copy padding-inline-small">
        <?php esc_html_e( $additional_copy ); ?>
    </div>
<?php endif; ?>
<?php vendi_load_modern_component( 'accordion/accordion-controls', object_state: [ 'accordion_items' => $component->getAccordionItems() ] ); ?>
    <div
        class="accordion-items"
        data-columns-count="<?php echo esc_attr( $component->getNumberOfColumns() ); ?>"
    >
        <?php

        $rows = [];
        while ( have_rows( 'accordion_items' ) ) {
            the_row();

            ob_start();
            vendi_load_modern_component( [ 'accordion', get_row_layout() ] );
            $rows[] = ob_get_clean();
        }

        // Just in case any are empty
        $rows = array_filter( $rows );

        if ( 1 === $component->getNumberOfColumns() ) {
            $columns = [ $rows ];
        } else {
            $columns = array_chunk( $rows, ceil( count( $rows ) / $component->getNumberOfColumns() ) );
        }

        array_walk(
            $columns,
            static function ( &$column ) {
                $column = '<div class="accordion-column">' . implode( '', $column ) . '</div>';
            }
        );

        // If there's more than one column, add a line between each
        echo implode( '<div class="line"></div>', $columns );

        ?>

    </div>
<?php

$component->renderComponentWrapperEnd();
