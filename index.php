<?php
use Webmozart\PathUtil\Path;
get_header();
?>

	<main>
		<a id="main-content" tabindex="-1"></a>

		<?php

            $path = Path::join(get_template_directory(), 'page-parts', '_breadcrumbs.php');
            if(is_readable($path)){
                include $path;
            }

            /*$path = Path::join(get_template_directory(), 'page-parts', '_content-components.php');
            if(is_readable($path)){
                include $path;
            }*/

            $path = Path::join(get_template_directory(), 'page-parts', '_main-loop.php');
            if(is_readable($path)){
                include $path;
            }
        ?>

	</main>
<?php
get_footer();
