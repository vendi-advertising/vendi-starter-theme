<?php

//******* LOOP
if ( have_posts() )
{
    while ( have_posts() )
    {
?>
        <div class="main-content">
            <div class="region main-content-region">
                <?php
                    the_post();

                    //the_title( '<h1 class="page-title">', '</h1>' );

                    //the_content();
                ?>
            </div>
        </div>
<?php
    }
}
