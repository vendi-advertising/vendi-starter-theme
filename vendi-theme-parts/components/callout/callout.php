<?php

use Vendi\Theme\Component\Callout;
use Vendi\Theme\DTO\SimpleLink;

$component = new Callout();

$component->renderComponentWrapperStart();

?>

<?php vendi_render_headline( 'header'); ?>
<?php echo $component->getSubField( 'copy' ); ?>
<?php if ( have_rows( 'buttons' ) ): ?>
    <div class="call-to-action-wrap">
        <?php while ( have_rows( 'buttons' ) ) : the_row(); ?>
            <?php if ( $link = SimpleLink::tryCreate( get_sub_field( 'call_to_action' ) ) ): ?>
                <?php echo $link->toHtml( cssClasses: [ 'call-to-action', 'call-to-action-button', get_sub_field( 'icon' ), get_sub_field( 'call_to_action_display_mode' ) ] ); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php

$component->renderComponentWrapperEnd();
