<?php

http_response_code(500);

$title = 'Theme error';
$msg = 'A theme error occurred, please contact the site administrator';
$devMsg = null;

$errorCode = defined('VENDI_EARLY_EXIT_ERROR_CODE') ? VENDI_EARLY_EXIT_ERROR_CODE : 0;

$missingPluginTemplateMessage = <<<'EOF'
The theme appears to be missing the <em>%s</em> plugin, please install and activate it.
EOF;


switch ($errorCode) {
    case VENDI_EARLY_EXIT_ERROR_CODE_MISSING_AUTOLOAD:
        $devMsg = <<<'EOF'
The theme is missing the composer autoload file, please ensure that <code>composer install</code> has been run.
EOF;
        break;

    case VENDI_EARLY_EXIT_ERROR_CODE_PHP_VERSION:

        $major = floor(VENDI_MINIMUM_PHP_VERSION_ID / 10000);
        $minor = floor((VENDI_MINIMUM_PHP_VERSION_ID - ($major * 10000)) / 100);
        $patch = VENDI_MINIMUM_PHP_VERSION_ID - ($major * 10000) - ($minor * 100);

        $devMsg = <<<'EOF'
The theme requires PHP version <code>${VENDI_MINIMUM_PHP_VERSION}</code> or higher, please upgrade your PHP version.
EOF;
        $devMsg = str_replace('${VENDI_MINIMUM_PHP_VERSION}', sprintf("%d.%d.%d", $major, $minor, $patch), $devMsg);
        break;

    case VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_ACF_PRO:
        $devMsg = sprintf($missingPluginTemplateMessage, 'Advanced Custom Fields Pro');
        break;

    case VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_FLY:
        $devMsg = sprintf($missingPluginTemplateMessage, 'Fly Dynamic Image Resizer');
        break;

    case VENDI_EARLY_EXIT_ERROR_CODE_MISSING_PLUGIN_CLASSIC_EDITOR:
        $devMsg = sprintf($missingPluginTemplateMessage, 'Classic Editor');
        break;

}

if (defined('WP_CLI')) {
    if (!$devMsg) {
        $devMsg = 'A Vendi Starter Theme error occurred, but no developer message was provided';
    }
    WP_CLI::error(strip_tags($devMsg), false);

    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Theme error</title>
    <style>
        html {
            font-size: 62.5%;
        }

        body {
            background-color: #ddd;
            font-family: sans-serif;
            font-size: 1.6rem;
        }

        .error-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0 auto;
            width: min(60rem, 90%);
            max-width: 80rem;
            min-height: 25rem;
            padding: 2rem;
            box-sizing: border-box;
            background-color: #fff;
            border: 1px solid #999;
            border-radius: 4px;
            color: #333;
        }

        .error-message details summary {
            font-weight: 700;
        }

        .error-message details p {
            padding-inline: 1.5rem;
        }
    </style>
</head>
<body>

<header>

</header>
<main>
    <div class="error-message">
        <h1><?php echo htmlspecialchars($title); ?></h1>
        <p><?php echo htmlspecialchars($msg); ?></p>
        <?php if ($devMsg) : ?>
            <details>
                <summary>Developer details</summary>
                <p><?php echo $devMsg; ?></p>
            </details>
        <?php endif; ?>
    </div>

</main>
<footer>

</footer>
</body>
</html>
