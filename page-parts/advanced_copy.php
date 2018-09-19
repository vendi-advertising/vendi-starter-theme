<?php
// get the Advanced Copy Block
if( get_row_layout() == 'advanced_copy' ):
    $formattagstart = "<" . get_sub_field ('advanced_copy_headline_format' ) . ">";
    $formattagend = "</" . get_sub_field ('advanced_copy_headline_format' ) . ">";
    $advancedimage = get_sub_field("advanced_copy_image");
    $advancedlink = get_sub_field("advanced_copy_link");
?>
<div class="advanced-copy">
	<div class="advanced-copy-region region">
        <?php if (get_sub_field ('advanced_copy_headline' )): ?>
            <?php echo $formattagstart; ?>
            <span class="advanced-copy-headline <?php the_sub_field('advanced_copy_headline_color'); ?>">
                <?php the_sub_field('advanced_copy_headline'); ?>
            </span>
            <?php echo $formattagend; ?>
		<?php endif; ?>
        <?php if (get_sub_field ('advanced_copy_body_copy' )): ?>
		<div class="advanced-copy-copy">
			<?php the_sub_field('advanced_copy_body_copy'); ?>
		</div>
		<?php endif; ?>
        <?php if( !empty($advancedimage) ): ?>
        <div class="advanced-copy-image">
            <img src="<?php echo $advancedimage['url']; ?>" alt="<?php echo $advancedimage['alt']; ?>" />
        </div>
        <?php endif; ?>
        <?php if( !empty($advancedlink) ): ?>
        <div class="advanced-copy-link">
            <a target="<?php echo $advancedlink['target']; ?>" href="<?php $advancedlink['url']; ?>"><?php echo $advancedlink['title']; ?></a>
        </div>
        <?php endif; ?>
	</div>
</div>
<?php
	endif;
	// end the Advanced Copy Block
?>