<?php

if ('none' === get_field('site_header')) {
    return;
}

?>
<header class="site-header">

    <?php vendi_load_component_v3('header/logo'); ?>

    <div class="nav-and-features">

        <?php
        vendi_load_component_v3('header/utility-nav');
        vendi_load_component_v3('header/primary-nav');
        ?>

        <div class="features-wrap">
            <?php vendi_load_component_v3('header/search-button'); ?>
            <?php vendi_load_component_v3('header/language-switcher'); ?>
        </div>
    </div>

    <?php vendi_load_component_v3('header/search-modal'); ?>

</header>
