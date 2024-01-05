<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php

    wp_head();

    ?>
</head>
<body <?php body_class(); ?>>

<a href="#main-content" class="visually-hidden focusable skip-link">
    Skip to main content
</a>

<header class="site-header">
    <div class="logo">
        <a href="<?php esc_attr_e(home_url()); ?>">
            <img alt="" src="<?php esc_attr_e(get_template_directory_uri()); ?>/images/starter-content/bird-logo.svg"/>
        </a>
    </div>
    <div class="header-navigation">
        <nav>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary_navigation',
                    'container' => false,
                    'container_class' => false,
                    'menu_class' => 'main-nav',
                    'fallback_cb' => 'false',
                    'depth' => 3,
                ]
            );
            ?>
        </nav>
    </div>
    <div class="header-features">
        <button type="button" class="search-activation-button" data-role="search-activation-button" data-target-id="site-search-modal">
            <?php vendi_get_svg('images/search-icon.svg'); ?>
            <span class="visually-hidden">
                Menu
            </span>
        </button>
        <dialog id="site-search-modal">
            <div class="search">
                <?php get_search_form(); ?>
            </div>
        </dialog>
        <?php // echo do_shortcode('[responsive_menu_pro]'); ?>
    </div>

</header>

<script>
    document
        .querySelectorAll('[data-role="search-activation-button"][data-target-id]')
        .forEach(
            (button) => {
                button
                    .addEventListener(
                        'click',
                        () => {
                            const
                                targetId = button.getAttribute('data-target-id'),
                                target = document.getElementById(targetId)
                            ;
                            if (!target) {
                                return;
                            }
                            target.showModal();
                        }
                    )
                ;
            }
        )
    ;
</script>
