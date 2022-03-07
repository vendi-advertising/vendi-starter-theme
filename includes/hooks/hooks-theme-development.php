<?php

use Vendi\Shared\utils;
use Webmozart\PathUtil\Path;

add_action(
    'vendi/component-loader/missing-template',
    static function ($name, $folders, $path) {

        $automaticallyCreateFilesIfTheyDontExist = true;

        if ($automaticallyCreateFilesIfTheyDontExist) {

            $host = utils::get_server_value('HTTP_HOST');
            $uri = utils::get_server_value('REQUEST_URI');

            // https://stackoverflow.com/a/6768831/231316
            $sourceUrl = (utils::get_server_value('HTTPS') === 'on' ? 'https' : 'http') . "://${host}${uri}";

            array_shift($folders);
            $shortPath = esc_html(implode('/', $folders));

            $fileName = array_pop($folders);

            $localPath = Path::join(VENDI_CUSTOM_THEME_PATH, ...$folders);
            if (!is_dir($localPath)) {

                // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
                if (!mkdir($localPath, 0777, true) && !is_dir($localPath)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $localPath));
                }
            }

            $html = "
             <div style=\"border: 2px solid red; padding: 40px;\">
                <h1 style=\"font-size: 48px;\">
                    This is an auto-generated file for the template.
                </h1>
                <code style=\"background-color: black; color: white; padding: 10px; margin: 10px 0; display: block;\">${shortPath}</code>.
                <!-- Template call first found at ${sourceUrl} -->
             </div>
             ";

            // NOTE: This code exists for debugging locally, only
            // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_touch
            touch($path);

            // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
            file_put_contents($path, $html);

            // We need to echo it here, too, because on first-pass the include just fails
            echo wp_kses(
                $html,
                [
                    'div' => ['style' => []],
                    'h1' => ['style' => []],
                    'code' => ['style' => []],
                ]
            );
        } else {
            dump($path);
        }
    },
    10,
    3
);

add_action(
    'vendi/component-loader/loading-template',
    static function ($name, $folders, $path) {

        $logToDisk = false;

        if ($logToDisk) {

            $host = utils::get_server_value('HTTP_HOST');
            $uri = utils::get_server_value('REQUEST_URI');

            // https://stackoverflow.com/a/6768831/231316
            $sourceUrl = (utils::get_server_value('HTTPS') === 'on' ? 'https' : 'http') . "://${host}${$uri}";

            array_shift($folders);
            $fileName = array_pop($folders);

            $localPath = Path::join(VENDI_CUSTOM_THEME_PATH, '.debug', 'components', ...$folders);
            if (!is_dir($localPath)) {

                // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
                if (!mkdir($localPath, 0777, true) && !is_dir($localPath)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $localPath));
                }
            }
            $localPath = Path::join($localPath, $fileName);

            // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
            file_put_contents(
                $localPath,
                sprintf(
                    '[%1$s] %2$s',
                    (new DateTime())->format('Y-m-d\TH:i:s.uP'),
                    $sourceUrl
                ),
                FILE_APPEND
            );

        }
    },
    10,
    3
);
