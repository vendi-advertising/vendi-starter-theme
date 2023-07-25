<?php

if (is_404() || is_front_page()) {
    return;
}

if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<nav aria-label="Breadcrumb"><div class="breadcrumbs-wrapper region"><div class="breadcrumbs">', '</div></div></nav>');
}
