<?php

use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php

    wp_head();

    ?>
</head>
<body <?php body_class(); ?>>

<a href="#main-content" class="visually-hidden focusable skip-link">
    Skip to main content
</a>

<?php vendi_render_feature('alerts', ['appearance' => AlertAppearanceEnum::AboveGlobalSiteHeader]); ?>

<?php vendi_load_component_v3('header'); ?>
