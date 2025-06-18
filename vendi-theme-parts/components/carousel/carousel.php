<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\Component\Carousel;
use Vendi\Theme\ComponentUtility;

/** @var Carousel $component */
$component = ComponentUtility::get_new_component_instance(Carousel::class);

if ( ! $component->renderComponentWrapperStart()) {
    return;
}

vendi_enqueue_slick(Path::join(VENDI_CUSTOM_THEME_COMPONENT_FOLDER_NAME, 'carousel', 'slick.js'));

$arrowRight = vendi_get_svg('images/icons/arrow-right', echo: false);
$arrowLeft  = vendi_get_svg('images/icons/arrow-left', echo: false);

?>
    <script>
        window.VENDI_CAROUSEL_ARROW_RIGHT = window.VENDI_CAROUSEL_ARROW_RIGHT || <?php echo json_encode( '<button class="slick-next slick-arrow" type="button" style=""><span class="slick-next-icon" aria-hidden="true">' .  $arrowRight . '</span></button>', JSON_THROW_ON_ERROR); ?>;
        window.VENDI_CAROUSEL_ARROW_LEFT = window.VENDI_CAROUSEL_ARROW_LEFT || <?php echo json_encode('<button class="slick-prev slick-arrow" type="button" style=""><span class="slick-prev-icon" aria-hidden="true">' . $arrowLeft . '</span></button>', JSON_THROW_ON_ERROR); ?>;
    </script>
<?php

$component->maybeRenderComponentHeading();
?>

    <ul class="carousel-slides" data-role="vendi-carousel-slides">
        <?php
        while ($component->haveRows('slides')) {
            $component->theRow();
            ?>
            <li class="carousel-slide <?php esc_attr_e(get_row_layout()); ?>" data-role="vendi-carousel-slide">
                <?php
                vendi_load_component_v3(['carousel', get_row_layout()]);
                ?>
            </li>
            <?php
        }
        ?>
    </ul>
<?php

$component->renderComponentWrapperEnd();
