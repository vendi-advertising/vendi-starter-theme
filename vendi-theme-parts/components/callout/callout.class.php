<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentStyles;
use Vendi\Theme\Traits\ImageSettingsTrait;
use Vendi\Theme\Traits\LinkColorSettingsTrait;
use Vendi\Theme\Traits\PrimaryTextColorSettingsTrait;

class Callout extends BaseComponentWithPrimaryHeading
{
    use PrimaryTextColorSettingsTrait;
    use LinkColorSettingsTrait;
    use ImageSettingsTrait;

    public function __construct()
    {
        parent::__construct('component-callout');
    }

    public function setComponentCssProperties(): void
    {
        $this->setComponentCssPropertiesForImageSettings($this->componentStyles);
        $this->setComponentCssPropertiesForLinkColorSettings($this->componentStyles);

        if (($image = $this->getSubField('image')) && 'image' === $this->getDisplayMode()) {
            if ($focal_point = sanitize_focal_point(get_post_meta($image['ID'], 'focal_point', true))) {
                [$x, $y] = $focal_point;
                $this->componentStyles->addCssProperty('--local-object-position', sprintf('%s%% %s%%', $x * 100, $y * 100));
            }
        }
    }

    private function getPattern(): ?string
    {
        return $this->getSubField('pattern');
    }

    protected function initDefaultRootClasses(): void
    {
        parent::initDefaultRootClasses();
        $this->addRootClass('display-mode-'.$this->getDisplayMode());
        if ($imagePlacement = $this->getImagePlacement()) {
            $this->addRootClass('image-placement-'.$imagePlacement);
        }

        if (($this->getSubField('image')) && 'pluses' === $this->getPattern()) {
            $this->addRootClass('has-pattern pattern-pluses');
        }
    }

    public function getDisplayMode(): ?string
    {
        return $this->getSubField('display_mode');
    }

    public function getCalloutImageHtml(): ?string
    {
        if ('image' !== $this->getDisplayMode()) {
            return null;
        }

        if (!$image = $this->getSubField('image')) {
            return null;
        }

        return $this->getImageHtml(
            $image['ID'],
            'full',
            attr: [
                'loading' => $this->getImageLoading(),
                'fetchpriority' => $this->getImageFetchPriority(),
            ],
        );
    }

    private function getImagePlacement(): ?string
    {
        return 'image' === $this->getDisplayMode() ? $this->getSubField('image_placement') : null;
    }

    private function getContentWrapperBackgrounds(): ?ComponentStyles
    {
        $style = new ComponentStyles();

        $key = 'content_backgrounds';
        $postId = null;
        $count = 0;
        if (have_rows($key, $postId)) {
            while (have_rows($key, $postId)) {
                the_row();

                $count++;

                $this->_vendi_get_background_settings_handle_layouts(false, $style);
            }
        }

        return $count >= 1 ? $style : null;
    }

    public function getCopy(): ?string
    {
        return $this->getSubField('copy');
    }

    public function jsonSerialize(): array
    {
        $ret = parent::jsonSerialize();

        $ret['copy'] = $this->getCopy();
        if (have_rows('buttons')) {
            $ret['buttons'] = [];
            while (have_rows('buttons')) {
                the_row();
                $ret['buttons'][] = [
                    'call_to_action' => $this->getSubField('call_to_action'),
                    'icon' => $this->getSubField('icon'),
                    'call_to_action_display_mode' => $this->getSubField('call_to_action_display_mode'),
                ];
            }
        }

        return $ret;
    }

    protected function renderAdditionalDefaultComponentStyles(): void
    {
        if (!$style = $this->getContentWrapperBackgrounds()) {
            return;
        }
        ?>
        [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"] .content-wrap :where(.content) {
        <?php echo $style->getDefaultStyleInformation(); ?>
        }
        <?php
    }
}
