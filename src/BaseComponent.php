<?php

namespace Vendi\Theme;

use JsonSerializable;
use WP_Post;

abstract class BaseComponent extends VendiComponent implements JsonSerializable
{
    public readonly ComponentStyles $componentStyles;
    private readonly int $componentIndex;

    public function __construct(
        string $componentName,
        public readonly bool $supportsBackgroundVideo = true,
        public readonly bool $supportsCommonContentAreaSettings = true,
        public array $additionalRootClasses = [],
    ) {
        parent::__construct($componentName, $additionalRootClasses);

        $this->componentStyles = new ComponentStyles();
        $this->componentIndex = ComponentUtility::get_instance()->get_next_id_for_component($this->componentName);

        $this->initDefaultRootClasses();
        $this->initRowId();
    }

    public function setComponentCssProperties(): void {}

    protected function initDefaultRootClasses(): void
    {
        if ($this->supportsBackgroundVideo) {
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
            $setting = 'content_width';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['full', 'narrow', 'slim'], 'full');
            $classes[] = 'content-max-width-'.$ret[$setting];
        }

        if ($value = $this->getSubField('content_placement')) {
            $setting = 'content_placement';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['left', 'middle'], 'left');
            $classes[] = 'content-placement-'.$ret[$setting];
        }

        if ($value = $this->getSubField('content_vertical_padding')) {
            $setting = 'content_vertical_padding';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
            $classes[] = 'content-vertical-padding-'.$ret[$setting];
        }

        if ($value = $this->getSubField('content_horizontal_padding')) {
            $setting = 'content_horizontal_padding';
            $ret[$setting] = vendi_constrain_item_to_list($value, ['xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none'], 'medium');
            $classes[] = 'content-horizontal-padding-'.$ret[$setting];
        }

        $ret['classes'] = $classes;

        return $ret;
    }

    private function _vendi_get_background_settings_handle_layouts(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        switch (get_row_layout()) {
            case 'background_color':
                $this->_vendi_get_background_settings_handle_layout_background_color($renderErrorMessagesForMissingValues, $style, $post_id);

                return;
            case 'background_image':
                $this->_vendi_get_background_settings_handle_layout_background_image($renderErrorMessagesForMissingValues, $style, $post_id);

                return;

            case 'background_video':
//            $this->_vendi_get_background_settings_handle_layout_background_video( $renderErrorMessagesForMissingValues, $style, $post_id );

                return;
            case 'linear_gradient':
                $this->_vendi_get_background_settings_handle_layout_linear_gradient($renderErrorMessagesForMissingValues, $style, $post_id);

                return;

            case 'reusable_background':
                if ((!$backgroundPost = get_sub_field('background')) || (!$backgroundPost instanceof WP_Post)) {
                    return;
                }

                if (have_rows('entity_backgrounds', $backgroundPost)) {
                    while (have_rows('entity_backgrounds', $backgroundPost)) {
                        the_row();

                        $this->_vendi_get_background_settings_handle_layouts($renderErrorMessagesForMissingValues, $style, $backgroundPost);
                    }
                }

                return;
        }
    }

    protected function getKeyForBackgrounds(): string
    {
        return 'backgrounds';
    }

    private function vendi_get_background_settings(ComponentStyles $previousStyles, bool $renderErrorMessagesForMissingValues = true, $key = 'backgrounds'): void
    {
        if (have_rows($key)) {
            while (have_rows($key)) {
                the_row();

                $this->_vendi_get_background_settings_handle_layouts($renderErrorMessagesForMissingValues, $previousStyles);
            }
        }
    }

    protected function renderComponentSpecificCssBlock(): void
    {
        $this->setComponentCssProperties();
        $this->vendi_get_background_settings($this->componentStyles, key: $this->getKeyForBackgrounds());

        ?>
        <style media="screen">
            [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"] {
            <?php echo $this->componentStyles->__toString(); ?>
            }
        </style>
        <?php
    }

    private function initRowId(): void
    {
        $row_id = $this->vendi_get_component_settings('component_row_id');

        if (!is_string($row_id) || empty($row_id)) {
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

        if (!parent::renderComponentWrapperStart()) {
            return false;
        }
        ?>
        
        <div
        class="component-wrapper"
        <?php $this->vendi_render_component_data_name_and_index_attributes($this->componentName, $this->getComponentIndex()); ?>
        >

        <?php
        if ($this->supportsBackgroundVideo) {
            $this->vendi_maybe_render_background_video();
        }
        ?>

        <div class="region">
        <div class="content-wrap">
        <?php

        return true;
    }

    public function renderComponentWrapperEnd(): void
    {
        echo ''; // This is just to make PHPStorm happy
        ?>
        </div>
        </div>
        </div>

        <?php
        parent::renderComponentWrapperEnd();
    }

    public function getImageHtml(int $imageId, string $size): string
    {
        return wp_get_attachment_image($imageId, $size);
    }

    public function jsonSerialize(): array
    {
        return [
            'componentName' => $this->componentName,
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

    private function _vendi_get_background_settings_handle_layout_linear_gradient(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        $angle = get_sub_field('angle');

        if (!$colors_stops = get_sub_field('colors_stops')) {
            $this->vendi_maybe_render_html_comment_error_message('Missing background_image_overlay_gradient', $renderErrorMessagesForMissingValues);

            return;
        }

        if (!is_array($colors_stops) || count($colors_stops) < 2) {
            $this->vendi_maybe_render_html_comment_error_message('Invalid colors_stops, must have at least two', $renderErrorMessagesForMissingValues);

            return;
        }

        $stopsAndColors = [];
        foreach ($colors_stops as $value) {
            $color = $value['color'] ?? '';
            $stop = $value['stop'] ?? '';
            $stop_unit = $value['stop_unit'] ?? '%';

            $stopsAndColors[] = "{$color} {$stop}{$stop_unit}";
        }

        $style->addBackgroundImage("linear-gradient({$angle}deg, ".implode(', ', $stopsAndColors).")");
        $style->addStyle('background-blend-mode', $this->vendi_get_background_blend_mode_from_settings(post_id: $post_id));
        $style->addStyle('background-size', $this->vendi_get_background_size_from_settings(post_id: $post_id));
        $style->addStyle('background-repeat', $this->vendi_get_background_repeat_from_settings(post_id: $post_id));
    }

    private function vendi_get_background_blend_mode_from_settings($default_value = 'normal', $post_id = false)
    {
        $ret = $default_value;
        if ($this->vendi_have_settings($post_id)) {
            while ($this->vendi_have_settings($post_id)) {
                the_setting();
                if ($blend_mode = get_sub_field('blend_mode')) {
                    $ret = $blend_mode;
                }
            }
        }

        return $ret;
    }

    private function vendi_get_background_repeat_from_settings($default_value = 'repeat', $post_id = false)
    {
        $ret = $default_value;
        if ($this->vendi_have_settings($post_id)) {
            while ($this->vendi_have_settings($post_id)) {
                the_setting();
                if ($background_repeat = get_sub_field('background_repeat')) {
                    $ret = $background_repeat;
                }
            }
        }

        return $ret;
    }

    private function vendi_get_background_size_from_settings($default_value = 'auto', $post_id = false)
    {
        $ret = $default_value;
        if ($this->vendi_have_settings($post_id)) {
            while ($this->vendi_have_settings($post_id)) {
                the_setting();
                if ($background_size = get_sub_field('background_size')) {
                    $ret = $background_size;
                }
            }
        }

        return $ret;
    }

    private function vendi_maybe_render_html_comment_error_message(string $errorText, bool $render = true): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG && $render) {
            echo sprintf('<!-- %s -->', esc_html($errorText));
        }
    }

    private function _vendi_get_background_settings_handle_layout_background_color(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        if (!$background_color = get_sub_field('background_color')) {
            $this->vendi_maybe_render_html_comment_error_message('Missing background_color', $renderErrorMessagesForMissingValues);

            return;
        }

        $style->addBackgroundImage("linear-gradient(0deg, {$background_color} 0%, {$background_color} 100%)");
        $style->addStyle('background-blend-mode', $this->vendi_get_background_blend_mode_from_settings(post_id: $post_id));
        $style->addStyle('background-size', $this->vendi_get_background_size_from_settings(post_id: $post_id));
        $style->addStyle('background-repeat', $this->vendi_get_background_repeat_from_settings(post_id: $post_id));
    }

    private function vendi_get_background_video_iframe(bool $renderErrorMessagesForMissingValues = true, string $key = 'backgrounds', ?WP_Post $post_id = null): ?string
    {
        $background_video = null;
        if (have_rows($key, $post_id)) {
            while (have_rows($key, $post_id)) {
                the_row();

                if ('background_video' === get_row_layout()) {
                    $background_video = get_sub_field('background_video');
                }
            }
        }

        if (!$background_video) {
            $this->vendi_maybe_render_html_comment_error_message('Missing background_video', $renderErrorMessagesForMissingValues);

            return null;
        }

        // Add autoplay functionality to the video code
        if (!preg_match('/src="(?<video>.+?)"/', $background_video, $matches)) {
            $this->vendi_maybe_render_html_comment_error_message('Could not find video URL in background_video', $renderErrorMessagesForMissingValues);
        }

        // Video source URL
        $src = $matches['video'];

        // Add option to hide controls, enable HD, and do autoplay -- depending on provider
        $params = [
            'playsinline' => 1,
            'controls' => 0,
            'hd' => 1,
            'autoplay' => 1,
            'background' => 1,
            'loop' => 1,
            'byline' => 0,
            'title' => 0,
            'muted' => 1,
            'mute' => 1,

        ];

        $new_src = add_query_arg($params, $src);

        $background_video = str_replace($src, $new_src, $background_video);

        // add extra attributes to iframe html
        $attributes = 'frameborder="0" autoplay muted loop playsinline webkit-playsinline';

        return str_replace('></iframe>', ' '.$attributes.'></iframe>', $background_video);
    }

    private function _vendi_get_background_settings_handle_layout_background_image(bool $renderErrorMessagesForMissingValues, ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        if (!$background_image = get_sub_field('background_image')) {
            $this->vendi_maybe_render_html_comment_error_message('Missing background_image', $renderErrorMessagesForMissingValues);

            return;
        }
        $style->addBackgroundImage("url('{$background_image['url']}')");
        $style->addStyle('background-blend-mode', $this->vendi_get_background_blend_mode_from_settings(post_id: $post_id));
        $style->addStyle('background-size', $this->vendi_get_background_size_from_settings(post_id: $post_id));
        $style->addStyle('background-repeat', $this->vendi_get_background_repeat_from_settings(post_id: $post_id));

        sanitize_focal_point($background_image['ID']);

        if ($focal_point = sanitize_focal_point(get_post_meta($background_image['ID'], 'focal_point', true))) {
            [$left, $top] = $focal_point;
            $left *= 100;
            $top *= 100;
            $style->addStyle('background-position', "{$left}% {$top}%");
        }
    }

    private function vendi_maybe_render_background_video(): void
    {
        if ($backgroundVideo = $this->vendi_get_background_video_iframe()) : ?>
            <div class="background-video">
                <?php echo $backgroundVideo; ?>
            </div>
        <?php
        endif;
    }

    private function vendi_render_component_data_name_and_index_attributes(string $componentName, int $componentIndex): void
    {
        $attributes = [
            'data-component-name' => $componentName,
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

    private function vendi_have_settings($post_id = false): bool
    {
        return have_rows('layout_settings', $post_id);
    }

    private function vendi_get_component_settings(string $name, mixed $default_value = null): mixed
    {
        // If there is a way to do this without loop, I haven't found it yet.
        if ($this->vendi_have_settings()) {
            while ($this->vendi_have_settings()) {
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
}
