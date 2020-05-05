<?php
// get the Quick links Block
if( get_row_layout() == 'quick_links' ):
?>
<div class="quick-links">
    <div class="quick-links-left">
        <div class="quick-links-left-wrapper">
            <div class="quick-links-left-container">
                <div class="quick-link-headline">
                    <h2><?php the_sub_field('quick_links_headline'); ?></h2>
                </div>
                <?php if (get_sub_field ('quick_links_body_copy' )): ?>
                <div class="quick-link-body-copy">
                    <?php the_sub_field('quick_links_body_copy'); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="quick-links-right">
        <div class="quick-links-right-wrapper">
            <?php
            // begin printing the Quick Link repeater
            if( have_rows('quick_links_links_repeater') ):
            ?>
            <?php while( have_rows('quick_links_links_repeater') ): the_row();
                // vars
                $quicklinkurl = get_sub_field('quick_links_link');
                $quicklinkicon = get_sub_field('quick_links_link_icon');
            ?>
            <div class="link">
                <div class="link-button equal-link">
                    <a target="<?php echo $quicklinkurl['target']; ?>" href="<?php echo $quicklinkurl['url']; ?>">
                        <span class="quick-link-text"><?php echo $quicklinkurl['title']; ?> <i class="fas fa-chevron-right fa-xs"></i></span> <span class="quick-link-icon"><img alt="<?php echo $quicklinkicon; ?>" src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $quicklinkicon; ?>.svg" /></span>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
            <?php endif;
            // end printing the Quick Link repeater
            ?>
        </div>
    </div>
</div>
<?php
	endif;
	// end the Quick links Block
?>