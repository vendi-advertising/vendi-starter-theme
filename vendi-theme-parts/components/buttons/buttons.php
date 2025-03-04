<?php

use Vendi\Theme\Component\Buttons;
use Vendi\Theme\ComponentUtility;

/** @var Buttons $component */
$component = ComponentUtility::get_new_component_instance(Buttons::class);

global $vendi_layout_component_object_state;

$field_for_buttons = $vendi_layout_component_object_state['field_for_buttons'] ?? 'buttons';

?>

<?php if ($component->haveRows($field_for_buttons)): ?>
    <div class="call-to-action-wrap">
        <?php while ($component->haveRows($field_for_buttons)) : $component->theRow(); ?>
            <?php vendi_load_component_v3(['buttons', 'button']); ?>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
