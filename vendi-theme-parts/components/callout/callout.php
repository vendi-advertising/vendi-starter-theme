<?php

use Vendi\Theme\Component\Callout;
use Vendi\Theme\ComponentUtility;
use Vendi\Theme\DTO\SimpleLink;

/** @var Callout $component */
$component = ComponentUtility::get_new_component_instance( Callout::class );

$component->renderComponentWrapperStart();

?>

<?php vendi_render_headline(); ?>
<?php echo $component->getCopy(); ?>
<?php if ( $component->haveRows( 'buttons' ) ): ?>
    <div class="call-to-action-wrap">
        <?php while ( $component->haveRows( 'buttons' ) ) : $component->theRow(); ?>
            <?php if ( $link = SimpleLink::tryCreate( $component->getSubField( 'call_to_action' ) ) ): ?>
                <?php echo $link->toHtml( cssClasses: [ 'call-to-action', 'call-to-action-button', $component->getSubField( 'icon' ), $component->getSubField( 'call_to_action_display_mode' ) ] ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php

$component->renderComponentWrapperEnd();
