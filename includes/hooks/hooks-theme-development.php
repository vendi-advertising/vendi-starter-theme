<?php

use Vendi\Shared\utils;
use Symfony\Component\Filesystem\Path;

add_action(
    'vendi/component-loader/missing-template',
    static function ($name, $folders, $path) {

        $automaticallyCreateFilesIfTheyDontExist = true;

        if ($automaticallyCreateFilesIfTheyDontExist) {

            $host = utils::get_server_value('HTTP_HOST');
            $uri = utils::get_server_value('REQUEST_URI');

            // https://stackoverflow.com/a/6768831/231316
            $sourceUrl = (utils::get_server_value('HTTPS') === 'on' ? 'https' : 'http') . "://$host$uri";

            array_shift($folders);
            $shortPath = esc_html(implode('/', $folders));

            // The last item is a file name, remove it
            array_pop($folders);

            $localPath = Path::join(VENDI_CUSTOM_THEME_PATH, ...$folders);
            if (!is_dir($localPath)) {

                // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
                if (!mkdir($localPath, 0777, true) && !is_dir($localPath)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $localPath));
                }
            }

            $html = <<<EOF
<?php

vendi_load_component_component_with_state('theme_building', ['short_path' => '$shortPath', 'source_url' => '$sourceUrl'] ,'debug');
EOF;

            // NOTE: This code exists for debugging locally, only
            // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_touch
            touch($path);

            // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
            file_put_contents($path, $html);

            include $path;
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
            $sourceUrl = (utils::get_server_value('HTTPS') === 'on' ? 'https' : 'http') . "://$host$uri";

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
