<?php

// begin breadcrumbs
if ( function_exists('yoast_breadcrumb') ) {
    echo '<div id="breadcrumbs"><div class="breadcrumbs-wrapper region">';
    yoast_breadcrumb('<div class="breadcrumbs">','</div>');
    echo '</div></div>';
}
// end breadcrumbs
