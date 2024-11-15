<?php

use Vendi\Theme\Component\Form;
use Vendi\Theme\ComponentUtility;

/** @var Form $component */
$component = ComponentUtility::get_new_component_instance(Form::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>

<?php vendi_render_heading($component); ?>
<?php echo $component->getSubField('copy'); ?>
<?php
if ($form_id = $component->getSubField('form')) {
    $display_form_title       = $component->getSubFieldBoolean('display_form_title', false);
    $display_form_description = $component->getSubFieldBoolean('display_form_description', false);
    $use_ajax                 = $component->getSubFieldBoolean('use_ajax', false);

    $form_html = gravity_form(
        $form_id,
        display_title: $display_form_title,
        display_description: $display_form_description,
        ajax: $use_ajax,
        echo: false,
    );

    $processor = new WP_HTML_Tag_Processor($form_html);
    if ($processor->next_tag(['tag_name' => 'INPUT', 'class_name' => 'gform-button'])) {
        $processor->remove_attribute('class');
//                        $processor->remove_class( 'gform-button' );
        $processor->add_class('call-to-action-button');
        $processor->add_class('blue-on-white');
    }

    echo $processor->get_updated_html();
}
$component->renderComponentWrapperEnd();
