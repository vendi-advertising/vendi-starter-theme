<?php

require_once __DIR__ . '/includes/functions.php';

global $vendi_selected_theme_page;


add_filter('show_admin_bar', '__return_false');

acf_form_head();

wp_enqueue_script('acf-input');
wp_enqueue_style('wp-admin');

//add_action(
//    'wp_enqueue_scripts',
//    static function () {
//        wp_dequeue_style('global-styles');
//        wp_deregister_style('global-styles');
//    },
//    PHP_INT_MAX,
//);


ob_start();
vendi_load_component_v3('accordion');
vendi_load_component_v3('accordion/accordion_item');
ob_end_clean();

?>
<!DOCTYPE html>
<html lang="en"">
<head>
    <?php
    wp_head();
    ?>
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
    <div class="logo">
        <a href="/__theme_docs/" rel="nofollow"><?php vendi_get_svg('images/starter-content/bird-logo.svg'); ?></a>
    </div>
    <div class="component-basic-copy content-max-width-full content-placement-left documentation-hero">
        <div class="component-wrapper">
            <div class="region documentation">
                <div class="content-wrap">
                    <h1>Theme documentation</h1>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <?php include __DIR__ . '/includes/nav.php'; ?>
    <div class="main-documentation-content">
        <div class="component-basic-copy content-max-width-full content-placement-left documentation-intro">
            <div class="component-wrapper">
                <div class="region documentation">
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
    <?php
    global $vendi_inline_style_buffer;

    if (is_array($vendi_inline_style_buffer)) {
        $html = '';
        foreach ($vendi_inline_style_buffer as $id => $inlineStyle) {
            if ( ! is_string($inlineStyle)) {
                continue;
            }

            $html .= "<style id=\"vendi-inline-style-$id\">$inlineStyle</style>";
        }
        echo $html;
    }
    ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
