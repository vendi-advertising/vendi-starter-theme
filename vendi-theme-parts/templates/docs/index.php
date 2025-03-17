<?php

require_once __DIR__ . '/includes/functions.php';

global $vendi_selected_theme_page;


add_filter('show_admin_bar', '__return_false');

acf_form_head();
//get_header();

// Enqueue WordPress admin styles

add_action(
    'wp_enqueue_scripts',
    static function () {
//        wp_deregister_style('common');
    },
    priority: 100,
);


wp_enqueue_script('acf-input');
wp_enqueue_style('wp-admin');

?>
<!DOCTYPE html>
<html lang="en"">
<head>
    <?php
    wp_head(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Theme Documentation</title>
    <meta name="robots" content="noindex, nofollow">

    <style>
        <?php echo file_get_contents(__DIR__ . '/docs.css'); ?>
    </style>
</head>
<body>
<header>
    <?php vendi_load_component_v3('header/logo'); ?>
    <div class="theme-doc-hero">
        <h1>Theme documentation</h1>
    </div>
</header>
<main>
    <?php include __DIR__ . '/includes/nav.php'; ?>
    <div>
        <div class="component-basic-copy content-max-width-full content-placement-left">
            <div class="component-wrapper">
                <div class="region">
                    <div class="content-wrap">
                        <?php if ('index' === $vendi_selected_theme_page): ?>
                            <h1>Please select a component</h1>
                        <?php else: ?>
                            <?php

                            echo vendi_theme_docs_get_documentation_for_component($vendi_selected_theme_page);

                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ('index' !== $vendi_selected_theme_page) {
            vendi_theme_docs_render_examples($vendi_selected_theme_page);
        }

        ?>
    </div>
</main>
<footer>
    <script>
        <?php echo file_get_contents(__DIR__ . '/docs.js'); ?>
    </script>
</footer>
<?php wp_footer(); ?>
</body>
</html>
