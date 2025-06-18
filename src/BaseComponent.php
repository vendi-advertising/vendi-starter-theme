<?php

namespace Vendi\Theme;

use JsonSerializable;
use Vendi\Theme\ComponentInterfaces\BackgroundAwareInterface;
use Vendi\Theme\ComponentInterfaces\CustomBackgroundElementInterface;
use Vendi\Theme\ComponentInterfaces\ImageSettingsAwareInterface;
use Vendi\Theme\ComponentInterfaces\IntroCopyInterface;
use Vendi\Theme\Traits\BackgroundAwareTrait;

abstract class BaseComponent extends VendiComponent implements JsonSerializable, BackgroundAwareInterface
{
    use BackgroundAwareTrait;

    public readonly ComponentStyles $componentStyles;
    private readonly int $componentIndex;

    public function __construct(
        string $componentName,
        public readonly bool $_supportsBackgroundVideo = true,
        public readonly bool $supportsCommonContentAreaSettings = true,
        public array $additionalRootClasses = [],
        public readonly bool $includeRegionWrap = true,
        public readonly bool $includeContentWrap = true,
        public readonly bool $_supportsPatternBackground = true,
        public readonly bool $supportsCustomBackgroundElement = false,
    ) {
        $this->componentStyles = new ComponentStyles();

        parent::__construct($componentName, $additionalRootClasses);

        $this->componentIndex = ComponentUtility::get_instance()->get_next_id_for_component($this->componentName);

        $this->initDefaultRootClasses();
        $this->initRowId();
    }

    public function supportsBackgroundVideo(): bool
    {
        return $this->_supportsBackgroundVideo;
    }

    public function supportsPatternBackground(): bool
    {
        return $this->_supportsPatternBackground;
    }

    protected function initComponent(): void
    {
        parent::initComponent();
        if ($this instanceof ImageSettingsAwareInterface) {
            $this->setComponentCssPropertiesForImageSettings($this->componentStyles);
        }
    }

    protected function getAdditionalRootAttributes(): array
    {
        if ($this instanceof IntroCopyInterface) {
            $this->maybeSetAriaDescription();
        }

        return parent::getAdditionalRootAttributes();
    }


    public function setComponentCssProperties(): void {}

    protected function initDefaultRootClasses(): void
    {
        if ($this->supportsBackgroundVideo()) {
            $this->addRootClass('component-background-video-wrapper');
        }

        if ($this->isAcfPreview()) {
            $this->addRootClass('acf-preview');
        }
        if ($this->supportsCommonContentAreaSettings) {
            $this->addRootClasses($this->getCommonContentAreaSettings()['classes']);
        }
    }

    final public function isAcfPreview(): bool
    {
        global $is_preview;

        return true === $is_preview;
    }

    public function getComponentIndex(): int
    {
        return $this->componentIndex;
    }

    protected function getCommonContentAreaFields(): array
    {
        return [
            'content_max_width',
            'content_placement',
            'content_vertical_padding',
            'content_horizontal_padding',
        ];
    }

    private function getCommonContentAreaSettings(): array
    {
        $ret = ['classes' => []];

        $classes = [];

        if ($value = $this->getSubField('content_width')) {
            $setting       = 'content_width';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['full', 'narrow', 'slim'], 'full');
            $classes[]     = 'content-max-width-' . $ret[$setting];
        }

        if ($value = $this->getSubField('content_placement')) {
            $setting       = 'content_placement';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['left', 'middle'], 'left');
            $classes[]     = 'content-placement-' . $ret[$setting];
        }

        if ($value = $this->getSubField('content_vertical_padding')) {
            $setting       = 'content_vertical_padding';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
            $classes[]     = 'content-vertical-padding-' . $ret[$setting];
        }

        if ($value = $this->getSubField('content_horizontal_padding')) {
            $setting       = 'content_horizontal_padding';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
            $classes[]     = 'content-horizontal-padding-' . $ret[$setting];
        }

        $ret['classes'] = $classes;

        return $ret;
    }


    private function getStyleTagStart(): string
    {
        return '<style media="screen" data-component-name="' . esc_attr($this->componentName) . '" data-component-index="' . esc_attr($this->getComponentIndex()) . '">';
    }

    private function getStyleTagEnd(): string
    {
        return '</style>';
    }

    public function hasCustomCss(): bool
    {
        return false;
    }

    public function getCustomCss(): ?string
    {
        return null;
    }

    protected function renderComponentSpecificCssBlock(): void
    {
        $this->setComponentCssProperties();

        if ($this instanceof BackgroundAwareInterface) {
            $this->getBackgroundSettings($this->componentStyles, key: $this->getFieldKeyForBackgrounds(), postId: $this->getPostIdForBackgrounds());
        }


        $defaultStyles     = $this->componentStyles->getDefaultStyleInformation();
        $visibleOnlyStyles = $this->componentStyles->getVisibleOnlyStyleInformation();
        if ( ! $defaultStyles && ! $visibleOnlyStyles && ! $this->hasCustomCss()) {
            return;
        }

        ob_start();

        echo $this->getStyleTagStart();

        if ($defaultStyles) {
            ?>

            [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"] {
            <?php echo $defaultStyles; ?>
            }

            <?php
        }

        $this->renderAdditionalDefaultComponentStyles();

        if ($visibleOnlyStyles) {
            ?>
            [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"][data-visible] {
            <?php echo $visibleOnlyStyles; ?>
            }
            <?php
        }

        $this->renderAdditionalVisibleOnlyComponentStyles();

        if ($this->hasCustomCss()) {
            echo $this->getCustomCss();
        }

        $css = ob_get_clean();
        echo $css;
//        wp_add_inline_style('component-core-css-999-last-css', $css);
        echo $this->getStyleTagEnd();
    }

    protected function renderAdditionalDefaultComponentStyles(): void {}

    protected function renderAdditionalVisibleOnlyComponentStyles(): void {}

    private function initRowId(): void
    {
        $row_id = $this->getComponentSettings('component_row_id');

        if ( ! is_string($row_id) || empty($row_id)) {
            return;
        }

        $this->addRootAttribute('id', $row_id);
    }

    public function renderComponentWrapperStart(): bool
    {
        if ($this->abortRender()) {
            return false;
        }

        $this->renderComponentSpecificCssBlock();

        if ( ! parent::renderComponentWrapperStart()) {
            return false;
        }
        ?>

        <div
        class="component-wrapper"
        <?php $this->renderComponentDataNameAndIndexAttributes($this->componentName, $this->getComponentIndex()); ?>
        >

        <?php
        if ($this->supportsBackgroundVideo()) {
            $this->maybeRenderBackgroundVideo();
        }

        if ($this->supportsPatternBackground()) {
            $this->handleBackgroundPattern();
        }

        if ($this instanceof CustomBackgroundElementInterface) {
            $this->handleCustomBackgroundElement();
        }

        if ($this->includeRegionWrap) {
            $this->renderRegionWrapStart();
        }

        if ($this->includeContentWrap) {
            $this->renderContentWrapStart();
        }


        return true;
    }

private function renderRegionWrapStart(): void
{
    ?>
    <div class="region">
    <?php
}

private function renderContentWrapStart(): void
{
    ?>
    <div class="content-wrap">
    <?php
}

private function renderRegionWrapEnd(): void
{
    ?>
    </div>
    <?php
}

private function renderContentWrapEnd(): void
{
    ?>
    </div>
    <?php
}

    public function renderComponentWrapperEnd(): void
    {
        if ($this->includeRegionWrap) {
            $this->renderRegionWrapEnd();
        }

        if ($this->includeContentWrap) {
            $this->renderContentWrapEnd();
        }

        ?>
        </div>

        <?php
        parent::renderComponentWrapperEnd();
    }

    public function getImageHtml(int $imageId, array|string $size, $crop = null, $attr = []): string
    {
        return bis_get_attachment_image($imageId, $size, $crop, $attr);
    }

    public function jsonSerialize(): array
    {
        return [
            'componentName'  => $this->componentName,
            'componentIndex' => $this->componentIndex,
        ];
    }

    final public function getSubFieldBoolean(string $sub_field, ?bool $default = null): bool
    {
        return 'true' === $this->getSubFieldConstrainedToList($sub_field, ['true', 'false'], $default);
    }

    final public function getSubFieldRangeInt(string $sub_field, int $min, int $max, $default = null): null|int|string
    {
        return $this->getSubFieldConstrainedToList($sub_field, array_map('strval', range($min, $max)), $default);
    }

    final public function getFieldConstrainedToList(string $field, array $options, $default = null, $post_id = false): null|int|string
    {
        return vendi_constrain_item_to_list(get_field($field, $post_id), $options, $default);
    }

    final public function getSubFieldConstrainedToList(string $sub_field, array $options, $default = null): null|int|string
    {
        return vendi_constrain_item_to_list($this->getSubField($sub_field), $options, $default);
    }

    public function getAttachmentImageSrc(array|int $imageArrayOrId, array|string $size): array
    {
        if (is_array($imageArrayOrId)) {
            $imageId = $imageArrayOrId['ID'] ?? -1;
        } else {
            $imageId = $imageArrayOrId;
        }

        return bis_get_attachment_image_src($imageId, $size);
    }

    public function getAttachmentImage(int $attachment_id = 0, array|string $size = '', mixed $crop = null, array $attr = []): ?string
    {
        return bis_get_attachment_image($attachment_id, $size, $crop, $attr);
    }

    private function renderComponentDataNameAndIndexAttributes(string $componentName, int $componentIndex): void
    {
        $attributes = [
            'data-component-name'  => $componentName,
            'data-component-index' => $componentIndex,
        ];

        echo implode(
            ' ',
            array_map(
                static fn($key) => sprintf("%s=\"%s\"", esc_attr($key), esc_attr($attributes[$key])),
                array_keys($attributes),
            ),
        );
    }

    private function haveSettings($post_id = false): bool
    {
        return have_rows('layout_settings', $post_id);
    }

    private function getComponentSettings(string $name, mixed $default_value = null): mixed
    {
        // If there is a way to do this without loop, I haven't found it yet.
        if ($this->haveSettings()) {
            while ($this->haveSettings()) {
                the_setting();
                if ($value = get_sub_field($name)) {
                    // This needs more testing, but I believe this is the correct
                    // way to "finish" the loop early.
                    acf_remove_loop();

                    return $value;
                }
            }
        }

        return $default_value;
    }

    protected function getSubFieldConstrainedToEnumCases(string $subFieldName, array $enumCases, $default = null): ?string
    {
        $options = array_column($enumCases, 'value');
        $value   = $this->getSubField($subFieldName);

        if (in_array($value, $options, true)) {
            return $value;
        }

        return $default;
    }
}
