<?php

use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;

?>
<!DOCTYPE html>
<html lang="en">


//Redirects to theme docs for VBT site. Remove when templating a new site.
<?php
//header('Location: ' . get_site_url() . '/__theme_docs');
?>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <?php

    wp_head();

    ?>
</head>
<body <?php body_class(); ?>>

<?php vendi_load_component_v3('skip-link'); ?>

<?php vendi_render_feature('alerts', ['appearance' => AlertAppearanceEnum::AboveGlobalSiteHeader]); ?>

<?php vendi_load_component_v3('header'); ?>
