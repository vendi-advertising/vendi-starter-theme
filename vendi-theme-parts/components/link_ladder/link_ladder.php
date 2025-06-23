<?php

use Vendi\Theme\Component\LinkLadder;
use Vendi\Theme\ComponentUtility;

/** @var LinkLadder $component */
$component = ComponentUtility::get_new_component_instance(LinkLadder::class);
if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>

    <div class="heading-and-intro-copy sub-component-basic-copy">
        <?php
        $component->maybeRenderComponentHeading();
        $component->maybeRenderIntroCopy();
        ?>
    </div>

    <div class="link-rows">
        <?php
        while ($component->haveRows('link_rows')) {
            $component->theRow();

            vendi_load_component_v3(['link_ladder', get_row_layout()]);
            ?>
            <hr class="separator"/>
            <?php
        }
        ?>
    </div>
<?php
$component->renderComponentWrapperEnd();

