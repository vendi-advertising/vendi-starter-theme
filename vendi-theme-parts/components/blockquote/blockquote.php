<?php

use Vendi\Theme\Component\Blockquote;
use Vendi\Theme\ComponentUtility;

/** @var Blockquote $component */
$component = ComponentUtility::get_new_component_instance(Blockquote::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$component->maybeRenderComponentHeader();

?>
    <blockquote>
        <div class="copy">
            <?php echo $component->getSubField('copy'); ?>
        </div>
        <?php if ($attribution = $component->getSubField('attribution')): ?>
            <footer class="attribution">
                <cite><?php echo $attribution; ?></cite>
            </footer>
        <?php endif; ?>
    </blockquote>

<?php

$component->renderComponentWrapperEnd();
