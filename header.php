<?php

use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;

?>
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

<?php vendi_render_feature( 'alerts', [ 'appearance' => AlertAppearanceEnum::AboveGlobalSiteHeader ] ); ?>

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
    <div class="mobile-header-navigation" data-role="mobileNavContainer">
        <div class="mobile-menu-button-container">
            <button type="button" class="mobile-menu-button button" data-role="mobileNavButton">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="150" height="150"><path d="M5 8a2 2 0 1 0 0 4h40a2 2 0 1 0 0-4H5zm0 15a2 2 0 1 0 0 4h40a2 2 0 1 0 0-4H5zm0 15a2 2 0 1 0 0 4h40a2 2 0 1 0 0-4H5z"/></svg>
            </button>
        </div>

        <?php
            $mainNavItems = wp_get_nav_menu_items('main-nav') ? json_encode(wp_get_nav_menu_items('main-nav')) : file_get_contents(__DIR__ . '/starter-data/starter-main-nav.json');
            $topNavItems = wp_get_nav_menu_items('top-nav') ? json_encode(wp_get_nav_menu_items('top-nav')) : file_get_contents(__DIR__ . '/starter-data/starter-top-nav.json');
        ?>

        <script>
            window.mainNav = <?php echo $mainNavItems;?>;
            window.topNav = <?php echo $topNavItems;?>;
        </script>

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
