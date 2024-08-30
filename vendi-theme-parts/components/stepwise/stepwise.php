<?php

use Vendi\Theme\GenericComponent;

$component = new GenericComponent( 'component-stepwise' );

/*
    --local-stepwise-number-background-color: purple;
    --local-stepwise-number-color: red;
 */
$step_identifier_settings                  = get_sub_field( 'step_identifier_settings' );
$step_identifier_settings_width            = ( $step_identifier_settings['width'] ?? '1' ) . 'rem';
$step_identifier_settings_padding          = ( $step_identifier_settings['padding'] ?? '2' ) . 'rem';
$step_identifier_settings_font_size        = ( $step_identifier_settings['font_size'] ?? '1.6' ) . 'rem';
$step_identifier_settings_border_width     = ( $step_identifier_settings['border_width'] ?? '1' ) . 'rem';
$step_identifier_settings_border_color     = ( $step_identifier_settings['border_color'] ?? 'green' );
$step_identifier_settings_background_color = ( $step_identifier_settings['background_color'] ?? 'green' );
$step_identifier_settings_color            = ( $step_identifier_settings['color'] ?? 'green' );


$line_settings       = get_sub_field( 'line_settings' );
$line_settings_width = ( $line_settings['width'] ?? '1' ) . 'rem';
$line_settings_color = ( $line_settings['color'] ?? 'green' );

$step_content_settings                = get_sub_field( 'step_content_settings' );
$step_content_settings_top_padding    = ( $step_content_settings['top_padding'] ?? '2' ) . 'rem';
$step_content_settings_bottom_padding = ( $step_content_settings['bottom_padding'] ?? '2' ) . 'rem';

$steps_general_settings            = get_sub_field( 'steps_general_settings' );
$steps_general_settings_column_gap = ( $steps_general_settings['column_gap'] ?? '2' ) . 'rem';


$component->componentStyles->addCssProperty( '--local-stepwise-number-width', $step_identifier_settings_width );
$component->componentStyles->addCssProperty( '--local-stepwise-number-padding', $step_identifier_settings_padding );
$component->componentStyles->addCssProperty( '--local-stepwise-number-font-size', $step_identifier_settings_font_size );
$component->componentStyles->addCssProperty( '--local-stepwise-number-border-width', $step_identifier_settings_border_width );
$component->componentStyles->addCssProperty( '--local-stepwise-number-border-color', $step_identifier_settings_border_color );
$component->componentStyles->addCssProperty( '--local-stepwise-number-background-color', $step_identifier_settings_background_color );
$component->componentStyles->addCssProperty( '--local-stepwise-number-color', $step_identifier_settings_color );

$component->componentStyles->addCssProperty( '--local-stepwise-line-width', $line_settings_width );
$component->componentStyles->addCssProperty( '--local-stepwise-line-color', $line_settings_color );

$component->componentStyles->addCssProperty( '--local-stepwise-content-top-padding', $step_content_settings_top_padding );
$component->componentStyles->addCssProperty( '--local-stepwise-content-bottom-padding', $step_content_settings_bottom_padding );

$component->componentStyles->addCssProperty( '--local-stepwise-column-gap', $steps_general_settings_column_gap );

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}
?>

<?php if ( have_rows( 'steps' ) ): ?>
    <ol class="steps">
        <?php while ( have_rows( 'steps' ) ): ?>
            <?php the_row(); ?>

            <li class="step">
                <span class="number"><?php esc_html_e( get_sub_field( 'step_identifier' ) ); ?></span>
                <h2 class="header"><?php esc_html_e( get_sub_field( 'heading' ) ); ?></h2>
                <?php if ( $copy = get_sub_field( 'copy' ) ): ?>
                    <div class="content">
                        <?php echo wp_kses_post( $copy ); ?>
                    </div>
                <?php else: ?>
                    <div class="content"></div>
                <?php endif; ?>

            </li>
        <?php endwhile; ?>
    </ol>
<?php endif; ?>

<?php

$component->renderComponentWrapperEnd();
