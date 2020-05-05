<?php

if (is_404() || is_front_page()) {
    return;
}

if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<div id="breadcrumbs"><div class="breadcrumbs-wrapper region"><div class="breadcrumbs">', '</div></div></div>');
}
