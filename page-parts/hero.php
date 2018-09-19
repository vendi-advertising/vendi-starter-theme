<?php

    if(get_field('hero_image')){
        ?>
    <div id="js-parallax-window" class="hero-image">
        <?php
            $heroimage = get_field('hero_image');
            $herolink = get_field('hero_link');
        ?>
        <?php if(get_field('hero_headline')): ?>
        <div class="hero-headline-container">
            <div class="container">
                <div class="hero-headline">
                    <?php the_field('hero_headline'); ?>
                </div>
                <?php if(get_field('hero_sub-head')): ?>
                <div class="hero-sub-head">
                    <?php the_field('hero_sub-head'); ?>
                </div>
                <?php endif; ?>
                <?php if(get_field('hero_copy')): ?>
                <div class="hero-copy">
                    <?php the_field('hero_copy'); ?>
                </div>
                <?php endif; ?>
                <?php if( !empty($herolink) ): ?>
                <div class="hero-link">
                    <a href="<?php echo $herolink['url']; ?>" target="<?php echo $herolink['target']; ?>"><?php echo $herolink['title']; ?></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-purple"></div>
        <?php endif; ?>
        <div id="js-parallax-background" class="parallax-hero" style="background-image: url(<?php echo $heroimage['url']; ?>); background-size: cover;">
        </div>
    </div>
        <?php
    } 
?>
