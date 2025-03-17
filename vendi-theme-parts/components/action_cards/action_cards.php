<?php

use Vendi\Theme\Component\ActionCards;
use Vendi\Theme\ComponentUtility;

/** @var ActionCards $component */
$component = ComponentUtility::get_new_component_instance(ActionCards::class);
if (!$component->renderComponentWrapperStart()) {
    return;
}

$component->maybeRenderComponentHeader(); ?>

    <ul class="action-cards">

        <?php while ($component->haveRows('cards')): ?>
            <?php $component->theRow(); ?>
            <li class="action-card">
                <?php vendi_load_component_v3(['action_cards', get_row_layout()]) ?>
            </li>
        <?php endwhile; ?>

    </ul>

<?php $component->renderComponentWrapperEnd();
