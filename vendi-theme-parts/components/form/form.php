<?php

use Vendi\Theme\Component\Form;

$component = new Form( 'component-form' );

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}

?>

<?php vendi_render_headline( 'header' ); ?>
<?php echo get_sub_field( 'copy' ); ?>
<?php
if ( $form_id = get_sub_field( 'form' ) ) {
    $display_form_title       = vendi_get_sub_field_boolean( 'display_form_title', false );
    $display_form_description = vendi_get_sub_field_boolean( 'display_form_description', false );
    $use_ajax                 = vendi_get_sub_field_boolean( 'use_ajax', false );

    $form_html = gravity_form(
        $form_id,
        display_title: $display_form_title,
        display_description: $display_form_description,
        ajax: $use_ajax,
        echo: false,
    );

    $processor = new WP_HTML_Tag_Processor( $form_html );
    if ( $processor->next_tag( [ 'tag_name' => 'INPUT', 'class_name' => 'gform-button' ] ) ) {
        $processor->remove_attribute( 'class' );
//                        $processor->remove_class( 'gform-button' );
        $processor->add_class( 'call-to-action-button' );
        $processor->add_class( 'blue-on-white' );
    }

    echo $processor->get_updated_html();
}
$component->renderComponentWrapperEnd();
