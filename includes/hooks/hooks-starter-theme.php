<?php

add_filter(
    'pre_wp_nav_menu',
    static function ($output, $args) {
        return <<<EOT
        <ul class="main-nav list-as-nav" data-disable-links>
            <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Page 1</a></li>
            <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Page 2</a></li>
            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a href="#">Page 3</a>
                <ul class="sub-menu">
                    <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Child A</a></li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Child B</a></li>
                    <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Child C</a></li>
                </ul>
            </li>
            <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Page 4</a></li>
            <li class="menu-item menu-item-type-post_type menu-item-object-page"><a href="#">Page 5</a></li>
        </ul>
EOT;

    },
    accepted_args: 2,
);

add_filter(
    'body_class',
    static function ($classes) {
        $classes[] = 'vendi-starter-theme';

        return $classes;
    },
);
