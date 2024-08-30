<?php

use Vendi\Theme\Component\Stats;

$component = new Stats();

if ( ! $component->renderComponentWrapperStart() ) {
    return;
}
?>
<?php if ( have_rows( 'stats' ) ): ?>
    <?php while ( have_rows( 'stats' ) ) : the_row(); ?>
        <div class="single-stat">
            <div class="stat"><?php echo get_sub_field( 'stat' ); ?></div>
            <div class="details"><?php echo get_sub_field( 'details' ); ?></div>
        </div>
        <hr/>
    <?php endwhile; ?>
<?php endif; ?>
<?php echo get_sub_field( 'copy' ); ?>

<?php
$component->renderComponentWrapperEnd();
