<?php
use Webmozart\PathUtil\Path;

// begin content components flexible content
// check if the flexible content field has rows of data
if(have_rows('page_components')){

    echo '<section class="content-components">';

        // loop through the content components rows of data
        while ( have_rows('page_components') ) {
            the_row();

            $file_name = Path::join( get_template_directory(),  . 'page-parts', Path::getFilename(get_row_layout() . '.php' ));

            if(file_exists($file_name)){
                include $file_name;
                continue;
            }else{
                echo sprintf( '<!-- NO FILE FOUND FOR LAYOUT: %1$s -->', esc_html(get_row_layout()));
            }
        }

    echo '</section>';
}else{
    // no layouts found
}// end content components flexible content
