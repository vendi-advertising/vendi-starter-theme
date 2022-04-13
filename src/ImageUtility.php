<?php

namespace Vendi\Theme;

use Symfony\Component\Filesystem\Path;

class ImageUtility extends UtilityBase
{
    public function does_webp_version_exist(string $webp_url): bool
    {
        // Grab the path of the image
        $path = wp_parse_url($webp_url, PHP_URL_PATH);
        if (!$path) {
            return false;
        }

        // This is a fix for a MS install in a folder because the previous call to wp_parse_url just gets
        // us the entire path of the URL. This may or may not work for subdomains, too.
        if (is_multisite() && $site = get_site()) {
            $path = mb_substr($path, mb_strlen($site->path));
        }

        // Assume that the path is relative to the site's root, and create an absolute version of that.
        $absPath = Path::join(ABSPATH, $path);

        // See if it exists on disk
        if (!is_readable($absPath)) {
            return false;
        }

        return true;
    }

    public function get_webp_url_for_image(string $img_url): ?string
    {
        $possible_url = $img_url.'.webp';

        return $this->does_webp_version_exist($possible_url) ? $possible_url : null;
    }


    public function get_tuple_for_style_block_and_html_attribute_for_background_image(string $img_url = null): array
    {
        if (!$img_url) {
            return [null, null];
        }

        $hash = hash('sha256', $img_url);
        $webp_url = null;
        if ($hash) {
            $webp_url = $this->get_webp_url_for_image($img_url);
        }

        $style = '<style>';
        if ($webp_url) {
            $style .= sprintf(
                '.supports-webp-yes [data-background-image-hash="%1$s"] {background-image: url("%2$s");}',
                esc_attr($hash),
                esc_attr($webp_url)
            );
        }

        $style .= sprintf(
            '[data-background-image-hash="%1$s"] {background-image: url("%2$s");}',
            esc_attr($hash),
            esc_attr($img_url)
        );

        $style .= '</style>';

        $html_attribute = sprintf('data-background-image-hash="%1$s"', esc_attr($hash));

        return [$style, $html_attribute];
    }
}
