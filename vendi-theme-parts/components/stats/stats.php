<?php

use Vendi\Theme\Component\Stats;
use Vendi\Theme\ComponentUtility;

/** @var Stats $component */
$component = ComponentUtility::get_new_component_instance(Stats::class);

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
<?php

?>
<?php if (have_rows('stats')): ?>
    <?php while (have_rows('stats')) : the_row(); ?>
        <?php vendi_load_component_v3(['stats', 'single-stat']); ?>
        <hr/>
    <?php endwhile; ?>
<?php endif; ?>

<?php $component->maybeRenderCallsToAction(); ?>

<?php
$component->renderComponentWrapperEnd();
