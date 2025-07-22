<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\ComponentStyles;
use WP_Post;

trait BackgroundAwareTrait
{
    public function getBackgroundSettings(ComponentStyles $previousStyles, $key = 'backgrounds', $postId = false): void
    {
        if (have_rows($key, $postId)) {
            while (have_rows($key, $postId)) {
                the_row();

                $this->_getBackgroundSettingsHandleLayouts($previousStyles);
            }
        }
    }

    public function _getBackgroundSettingsHandleLayouts(ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        switch (get_row_layout()) {
            case 'background_color':
                $this->handleBackgroundLayoutBackgroundColor($style, $post_id);

                return;
            case 'background_image':
                $this->handleBackgroundLayoutBackgroundImage($style, $post_id);


                return;
            case 'linear_gradient':
                $this->handleBackgroundLayoutLinearGradient($style, $post_id);

                return;

            case 'reusable_background':
                if (( ! $backgroundPost = get_sub_field('background')) || ( ! $backgroundPost instanceof WP_Post)) {
                    return;
                }

                if (have_rows('entity_backgrounds', $backgroundPost)) {
                    while (have_rows('entity_backgrounds', $backgroundPost)) {
                        the_row();

                        $this->_getBackgroundSettingsHandleLayouts($style, $backgroundPost);
                    }
                }

                return;

            // These two are not true backgrounds and are instead handled elsewhere
            case 'background_video':
            case 'background_pattern':

                return;
            default:
                echo '<h1>Unknown layout type: ' . get_row_layout() . '</h1>';
        }
    }

    private function handleBackgroundLayoutBackgroundColor(ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        if ( ! $background_color = get_sub_field('background_color')) {
            return;
        }

        $style->addBackgroundImage("linear-gradient(0deg, {$background_color} 0%, {$background_color} 100%)");
        $style->addStyle('background-blend-mode', $this->getBackgroundSettingBlendMode(post_id: $post_id));
        $style->addStyle('background-size', $this->getBackgroundSettingSize(post_id: $post_id));
        $style->addStyle('background-repeat', $this->getBackgroundSettingRepeat(post_id: $post_id));
    }

    private function handleBackgroundLayoutBackgroundImage(ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        if ( ! $background_image = get_sub_field($this->getFieldKeyForBackgroundImage())) {
            return;
        }

        $background_image = $this->performAdditionalActionsOnBackgroundImage($background_image);

        $realUrl = $background_image['url'];

        if ($this->useLazyLoadingForBackgroundImages()) {
            $placeholderUrl = self::dataUriForTemporaryBackgroundImage;
            $this->addRootClass('has-lazy-background-image');
        } else {
            $placeholderUrl = null;
        }

        $style->addBackgroundImageUrl(trueImage: $realUrl, placeholderImage: $placeholderUrl);
        $style->addStyle('background-blend-mode', $this->getBackgroundSettingBlendMode(post_id: $post_id));
        $style->addStyle('background-size', $this->getBackgroundSettingSize(post_id: $post_id));
        $style->addStyle('background-repeat', $this->getBackgroundSettingRepeat(post_id: $post_id));

        if ($focal_point = sanitize_focal_point(get_post_meta($background_image['ID'], 'focal_point', true))) {
            [$left, $top] = $focal_point;
            $left *= 100;
            $top  *= 100;
            $style->addStyle('background-position', "{$left}% {$top}%");
        }
    }

    private function handleBackgroundLayoutLinearGradient(ComponentStyles $style, ?WP_Post $post_id = null): void
    {
        $angle = get_sub_field('angle');

        if ( ! $colors_stops = get_sub_field('colors_stops')) {
            return;
        }

        if ( ! is_array($colors_stops) || count($colors_stops) < 2) {
            return;
        }

        $stopsAndColors = [];
        foreach ($colors_stops as $value) {
            $color     = $value['color'] ?? '';
            $stop      = $value['stop'] ?? '';
            $stop_unit = $value['stop_unit'] ?? '%';

            $stopsAndColors[] = "{$color} {$stop}{$stop_unit}";
        }

        $style->addBackgroundImage("linear-gradient({$angle}deg, " . implode(', ', $stopsAndColors) . ")");
        $style->addStyle('background-blend-mode', $this->getBackgroundSettingBlendMode(post_id: $post_id));
        $style->addStyle('background-size', $this->getBackgroundSettingSize(post_id: $post_id));
        $style->addStyle('background-repeat', $this->getBackgroundSettingRepeat(post_id: $post_id));
    }

    private function getBackgroundSettingBlendMode($default_value = 'normal', $post_id = false)
    {
        $ret = $default_value;
        if ($this->haveSettings($post_id)) {
            while ($this->haveSettings($post_id)) {
                the_setting();
                if ($blend_mode = get_sub_field('blend_mode')) {
                    $ret = $blend_mode;
                }
            }
        }

        return $ret;
    }

    private function getBackgroundSettingRepeat($default_value = 'repeat', $post_id = false)
    {
        $ret = $default_value;
        if ($this->haveSettings($post_id)) {
            while ($this->haveSettings($post_id)) {
                the_setting();
                if ($background_repeat = get_sub_field('background_repeat')) {
                    $ret = $background_repeat;
                }
            }
        }

        return $ret;
    }

    private function getBackgroundSettingSize($default_value = 'auto', $post_id = false)
    {
        $ret = $default_value;
        if ($this->haveSettings($post_id)) {
            while ($this->haveSettings($post_id)) {
                the_setting();
                if ($background_size = get_sub_field('background_size')) {
                    $ret = $background_size;
                }
            }
        }

        return $ret;
    }

    private function getBackgroundVideoIframe(string $key = 'backgrounds', ?WP_Post $post_id = null): ?string
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

        if ( ! $background_video) {
            return null;
        }

        // Add autoplay functionality to the video code
        if ( ! preg_match('/src="(?<video>.+?)"/', $background_video, $matches)) {
            return null;
        }

        // Video source URL
        $src = $matches['video'];

        // Add option to hide controls, enable HD, and do autoplay -- depending on provider
        $params = [
            'playsinline' => 1,
            'controls'    => 0,
            'hd'          => 1,
            'autoplay'    => 1,
            'background'  => 1,
            'loop'        => 1,
            'byline'      => 0,
            'title'       => 0,
            'muted'       => 1,
            'mute'        => 1,

        ];

        $new_src = add_query_arg($params, $src);

        $background_video = str_replace($src, $new_src, $background_video);

        // add extra attributes to iframe html
        $attributes = 'frameborder="0" autoplay muted loop playsinline webkit-playsinline';

        return str_replace('></iframe>', ' ' . $attributes . '></iframe>', $background_video);
    }

    protected function getFieldKeyForBackgrounds(): string
    {
        return 'backgrounds';
    }

    protected function getPostIdForBackgrounds(): mixed
    {
        return null;
    }

    protected function getFieldKeyForBackgroundImage(): string
    {
        return 'background_image';
    }

    private const string dataUriForTemporaryBackgroundImage = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    protected function useLazyLoadingForBackgroundImages(): bool
    {
        return true;
    }

    protected function performAdditionalActionsOnBackgroundImage(array $background_image): array
    {
        return $background_image;
    }

    private function maybeRenderBackgroundVideo(): void
    {
        if ($backgroundVideo = $this->getBackgroundVideoIframe()) : ?>
            <div class="background-video">
                <?php echo $backgroundVideo; ?>
            </div>
        <?php
        endif;
    }

    private function handleBackgroundPattern(): void
    {
        if (have_rows('backgrounds')) {
            while (have_rows('backgrounds')) {
                the_row();

                if ('background_pattern' === get_row_layout()) {
                    if ( ! $background_pattern = get_sub_field('background_pattern')) {
                        continue;
                    }

                    if ( ! $background_color = get_sub_field('background_color')) {
                        continue;
                    }

                    if ('shields' !== $background_pattern) {
                        continue;
                    }

                    $backgroundSvg = $this->getBackgroundPatternShieldWithColor(get_sub_field('pattern_color'), true);

                    $props = [
                        '--local-background-pattern' => $backgroundSvg,
                        '--local-background-color'   => $background_color,
                    ];
                    // Convert to style attribte
                    $style = '';
                    foreach ($props as $key => $value) {
                        $style .= "{$key}: {$value};";
                    }

                    ?>
                    <div class="shields custom-colors" style="<?php echo $style; ?>"></div>
                    <?php
                }
            }
        }
    }

    public function getBackgroundPatternShieldWithColor(string $color, bool $base64EncodeAndWrapWithUrl = false): string
    {
        /**
         * NOTE: The following SVG has the literal "#RED" hard-coded into it, and we're finding and replacing
         * it later.
         */
        $background = <<<EOT
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 113.5 153.1">
                <path d="M56.5 152.5.5 107V12S27.5.5 56.5.5c29.3 0 56.5 12 56.5 12V107l-56.5 45.5Z" style="fill:none;stroke:#RED;stroke-miterlimit:10;stroke-width:2;"/>
            </svg>
            EOT;

        $background = str_replace('#RED', $color, $background);

        if ($base64EncodeAndWrapWithUrl) {
            $background = "url('data:image/svg+xml;base64," . base64_encode($background) . "')";
        }


        return $background;
    }
}
