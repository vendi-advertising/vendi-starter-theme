<?php

use Vendi\Theme\Component\Cards;
use Vendi\Theme\ComponentUtility;

/** @var Cards $component */
$component = ComponentUtility::get_new_component_instance(Cards::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

$component->maybeRenderComponentHeading();
$component->maybeRenderIntroCopy();

// This needs to be done outside of the loop
$cards = get_sub_field('cards');
?>

<?php if (have_rows('cards')): ?>
    <ul class="cards" data-card-count="<?php esc_attr_e(count($cards)); ?>">
        <?php while (have_rows('cards')) : ?>
            <?php the_row(); ?>

            <li class="card">
                <?php vendi_load_component_v3(['cards', get_row_layout()]); ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>


<?php $component->maybeRenderCallsToAction(); ?>

<?php

$component->renderComponentWrapperEnd();
