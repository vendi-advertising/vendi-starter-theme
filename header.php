<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php

	wp_head();

	?>
    <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
</head>
<body <?php body_class(); ?>>

	<a href="#main-content" class="visually-hidden focusable skip-link">
		Skip to main content
	</a>

	<!-- begin header -->
	<header>
        <div id="header">
            <div class="header-branding">
                <div class="header-logo" id="logo">
                        <a href="<?php echo home_url(); ?>">
                            <img alt="" src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" />
                        </a>
                </div>
                <div class="header-navigation">
                    <nav>
                        <div id="navigation">
                            <?php
                                wp_nav_menu(
                                    array(
                                            'theme_location'  => 'main_nav',
                                            'container'       => false,
                                            'container_class' => false,
                                            'menu_class'      => 'main-nav',
                                            'fallback_cb'     => 'false',
                                            'depth'           => 3,
                                        )
                                );
                            ?>
                        </div>
                    </nav>
                </div>
                <div class="header-features">
                    <?php echo do_shortcode('[responsive_menu_pro]'); ?>
                    <div class="search">
					    <?php get_search_form(); ?>
      	            </div>
                </div>
            </div>
        </div>
    </header>
    <!-- end header -->

    <?php include 'page-parts/hero.php'; ?>
