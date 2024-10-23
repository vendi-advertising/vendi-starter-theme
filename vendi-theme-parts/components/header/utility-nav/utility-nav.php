<?php

wp_nav_menu(
    [
        'theme_location'  => 'utility_navigation',
        'container'       => 'nav',
        'container_class' => 'utility-nav-wrap',
        'menu_class'      => 'utility-nav list-as-nav',
        'fallback_cb'     => 'false',
        'depth'           => 3,
    ],
);


