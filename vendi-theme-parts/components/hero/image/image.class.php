<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponent;
use Vendi\Theme\ComponentInterfaces\CallsToActionAwareInterface;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\ComponentInterfaces\CustomBackgroundElementInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use Vendi\Theme\Traits\CommonCallsToActionTrait;

use function bis_get_attachment_image;
use function bis_get_attachment_image_src;

class ImageHero extends BaseComponent implements ColorSchemeAwareInterface, CustomBackgroundElementInterface, CallsToActionAwareInterface
{
    use ColorSchemeTrait;
    use CommonCallsToActionTrait;

    public function __construct()
    {
        parent::__construct('component-hero-image');
    }

    protected function performAdditionalActionsOnBackgroundImage(array $background_image): array
    {
        static $cache = [];

        if ($ret = bis_get_attachment_image_src($background_image['ID'], [1920, 800])) {
            $ret['url'] = $ret['src'];
            $ret['ID']  = $background_image['ID'];

            $hash = md5(serialize($background_image));
            if ( ! isset($cache[$hash])) {
                add_action(
                    'wp_head',
                    static function () use ($ret) {
                        ?>
                        <link rel="preload" fetchpriority="high" as="image" href="<?php esc_attr_e($ret['url']); ?>">
                        <?php
                    },
                );
            }

            return $ret;
        }

        return $background_image;
    }

    protected function initComponent(): void
    {
        parent::initComponent();

        if ( ! $hero_height = $this->getSubField('hero_height')) {
            $hero_height = 'large';
        }

        $this->addRootClass('hero-height-' . $hero_height);

        if ($background_color_color = $this->getSubField('background_color_color')) {
            $this->componentStyles->addCssProperty('local-hero-background-color', $background_color_color);
        }

        if ($line_color_color = $this->getSubField('line_color_color')) {
            $this->componentStyles->addCssProperty('local-hero-line-color', $line_color_color);
        }
    }

    public function handleCustomBackgroundElement(): void
    {
        if ( ! $image = get_sub_field('background_image')) {
            return;
        }
        ?>

        <div class="background-image">
            <?php echo bis_get_attachment_image($image['ID'], 'full'); ?>
        </div>
        <?php
    }
}
