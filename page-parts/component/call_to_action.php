<?php
// get the Call to Action Block
if( get_row_layout() == 'call_to_action' ):
    $form = get_sub_field('call_to_action_form');
?>
<div class="call-to-action">
	<div class="call-to-action-region region">
        <?php if (get_sub_field ('call_to_action_headline' )): ?>
		<div class="call-to-action-headline">
			<h2><?php the_sub_field('call_to_action_headline'); ?></h2>
		</div>
		<?php endif; ?>
        <?php if (get_sub_field ('call_to_action_description' )): ?>
		<div class="call-to-action-description">
			<?php the_sub_field('call_to_action_description'); ?>
		</div>
		<?php endif; ?>
        <?php if (get_sub_field ('call_to_action_form' )): ?>
		<div class="call-to-action-form">
			<?php 
                gravity_form($form, false, false, false, '', true, 1);
            ?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
	endif;
	// end the Call to Action Block
?>