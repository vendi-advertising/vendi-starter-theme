<?php

use Vendi\Theme\Component\Button;
use Vendi\Theme\ComponentUtility;
use Vendi\Theme\DTO\SimpleLink;

/** @var Button $component */
$component = ComponentUtility::get_new_component_instance(Button::class);

if ($link = SimpleLink::tryCreate($component->getSubField('call_to_action'))): ?>
    <?php echo $link->toHtml(cssClasses: ['call-to-action', 'call-to-action-button', $component->getSubField('icon'), $component->getSubField('call_to_action_display_mode')]); ?>
<?php endif; ?>
