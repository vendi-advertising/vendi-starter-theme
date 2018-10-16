<?php

    use Webmozart\PathUtil\Path;

    get_header();
?>

    <main>
        <a id="main-content" tabindex="-1"></a>

        <?php
            $cc_path = Path::join(get_template_directory(),  'page-parts', 'content-components.php');
            if(is_readable($cc_path)){
                include $cc_path;
            }

            $ml_path = Path::join(get_template_directory(),  'page-parts', 'main-loop.php');
            if(is_readable($ml_path)){
                include $ml_path;
            }
        ?>

    </main>

<?php
get_footer();
