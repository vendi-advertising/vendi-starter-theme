<?php
// get the Feature Dock Block
if( get_row_layout() == 'feature_dock' ):
?>
<div class="feature-dock">
    <div class="feature-dock-heading">
        <h2><?php the_sub_field('feature_dock_headline'); ?></h2>
        <?php if (get_sub_field ('feature_dock_description' )): ?>
        <div class="feature-dock-heading-description">
            <?php the_sub_field('feature_dock_description'); ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="dock-features">
        <?php
        // begin printing the  Dock repeater
        if( have_rows('feature_dock_repeater') ):
            $rank = 1;
        ?>
        <?php while( have_rows('feature_dock_repeater') ): the_row();
            // vars
            $featuretitle = get_sub_field('feature_title');
            $featuredescription = get_sub_field('feature_description');
            $featurelink = get_sub_field('feature_link');
            $featureicon = get_sub_field('feature_icon');
            $featurebkgimage = get_sub_field('feature_background_image');
        ?>
        <div class="dock-feature column-<?php echo $rank; ?>" data-hover-and-click>
            <div class="feature-icon">
                <img alt="<?php echo $featureicon; ?>" src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $featureicon; ?>.svg" />
            </div>
            <div class="feature-title">
                <h3><?php echo $featuretitle; ?></h3>
            </div>
            <div class="feature-hover" style="background-image: url(<?php echo $featurebkgimage['url']; ?>); background-size: cover;">
                <a target="<?php echo $$featurelink['target']; ?>" href="<?php echo $$featurelink['url']; ?>">
                    <h3><?php echo $featuretitle; ?></h3>
                    <div class="feature-description">
                        <?php echo $featuredescription; ?>
                    </div>
                    <div class="feature-link">
                        <?php echo  $featurelink['title']; ?>
                    </div>
                </a>
            </div>
        </div>
        <?php $rank++; ?>
        <?php endwhile; ?>
        <?php endif;
        // end printing the Dock repeater
        ?>
    </div>
</div>
<?php
	endif;
	// end the Feature Dock Block
?>