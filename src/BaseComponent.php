<?php

namespace Vendi\Theme;

use JsonSerializable;

abstract class BaseComponent implements JsonSerializable
{

    public readonly ComponentStyles $componentStyles;
    private readonly int $componentIndex;
    protected array $fieldCache = [];

    public function __construct(
        public readonly string $componentName,
        public readonly bool $supportsBackgroundVideo = true,
        public readonly bool $supportsCommonContentAreaSettings = true,
    ) {
        $this->componentStyles = new ComponentStyles();
        $this->componentIndex = ComponentUtility::get_instance()->get_next_id_for_component($this->componentName);

        $this->initComponent();
    }

    protected function initComponent(): void {}

    public function setComponentCssProperties(): void {}

    protected function abortRender(): bool
    {
        return false;
    }

    /**
     * This function is a pass-through to get_sub_field() for normal template
     * usage, but it allows overriding in when running in test/documentation
     * mode.
     *
     * @param string $fieldName
     *
     * @return mixed
     */
    public function getSubField(string $fieldName): mixed
    {
        if (!isset($this->fieldCache[$fieldName])) {
            $this->fieldCache[$fieldName] = get_sub_field($fieldName);
        }

        return $this->fieldCache[$fieldName];
    }

    public function haveRows($selector, $post_id = false): bool
    {
        return have_rows($selector, $post_id);
    }

    public function theRow($format = false): void
    {
        the_row($format);
    }

    public function getRootClasses(): array
    {
        $ret = [
            $this->componentName,
        ];

        if ($this->supportsBackgroundVideo) {
            $ret[] = 'component-background-video-wrapper';
        }

        if ($this->supportsCommonContentAreaSettings) {
            $ret = array_merge($ret, $this->getCommonContentAreaSettings()['classes']);
        }

        return array_filter(array_merge($ret, $this->getAdditionalRootClasses()));
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

        $settingsGroup = $this->getSubField('content_area_settings');

        if (!$settingsGroup) {
            return $ret;
        }

        // NOTE: XX-Large is not included in the UI currently but was left here for consistency
        $settings = $this->getCommonContentAreaFields();

        $classes = [];

        foreach ($settings as $setting) {
            if (!$value = $settingsGroup[$setting] ?? null) {
                continue;
            }
            switch ($setting) {
                case 'content_max_width':
                    $ret[$setting] = vendi_constrain_item_to_list($value, ['full', 'narrow', 'slim'], 'narrow');
                    $classes[] = 'content-max-width-'.$ret[$setting];
                    break;
                case 'content_placement':
                    $ret[$setting] = vendi_constrain_item_to_list($value, ['left', 'middle'], 'left');
                    $classes[] = 'content-placement-'.$ret[$setting];
                    break;
                case 'content_vertical_padding':
                    $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
                    $classes[] = 'content-vertical-padding-'.$ret[$setting];
                    break;
                case 'content_horizontal_padding':
                    $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
                    $classes[] = 'content-horizontal-padding-'.$ret[$setting];
                    break;
            }
        }

        $ret['classes'] = $classes;

        return $ret;
    }

    protected function getKeyForBackgrounds(): string
    {
        return 'backgrounds';
    }

    protected function renderComponentSpecificCssBlock(): void
    {
        $this->setComponentCssProperties();
        vendi_get_background_settings($this->componentStyles, key: $this->getKeyForBackgrounds());
        ?>
        <style media="screen">
            [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"] {
            <?php echo $this->componentStyles->__toString(); ?>
            }
        </style>
        <?php
    }

    protected function getAdditionalRootAttributes(): array
    {
        return [];
    }

    protected function getAdditionalRootClasses(): array
    {
        return [];
    }

    protected function getRootTag(): string
    {
        return 'section';
    }

    public function renderComponentWrapperStart(): bool
    {
        if ($this->abortRender()) {
            return false;
        }

        $this->renderComponentSpecificCssBlock();
        echo '<'.$this->getRootTag().' ';

        vendi_render_class_attribute($this->getRootClasses());
        foreach ($this->getAdditionalRootAttributes() as $key => $value) {
            if (null === $value) {
                echo esc_attr($key);
            } else {
                echo sprintf('%s="%s"', esc_attr($key), esc_attr($value));
            }
        }
        vendi_render_row_id_attribute()
        ?>
        >
        <div
        class="component-wrapper"
        <?php
        vendi_render_component_data_name_and_index_attributes($this->componentName, $this->getComponentIndex()); ?>
        >

        <?php
        if ($this->supportsBackgroundVideo) {
            vendi_maybe_render_background_video();
        }
        ?>

        <div class="region">
        <div class="content-wrap">
        <?php

        return true;
    }

    public function renderComponentWrapperEnd(): void
    {
        ?>
        </div>
        </div>
        </div>

        <?php
        echo '</'.$this->getRootTag().'>';
    }

    public function jsonSerialize(): array
    {
        return [
            'componentName' => $this->componentName,
            'componentIndex' => $this->componentIndex,
        ];
    }

    public function getSubFieldBoolean(string $sub_field, ?bool $default = null, array $options = ['true', 'false']): bool
    {
        return 'true' === $this->getSubFieldConstrainedToList($sub_field, $options, $default);
    }

    public function getSubFieldRangeInt(string $sub_field, int $min, int $max, $default = null): null|int|string
    {
        return $this->getSubFieldConstrainedToList($sub_field, array_map('strval', range($min, $max)), $default);
    }

    public function getSubFieldConstrainedToList(string $sub_field, array $options, $default = null): null|int|string
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
}
