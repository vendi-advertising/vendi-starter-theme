<?php

use Symfony\Component\Filesystem\Path;

global $vendi_selected_theme_page;
global $vendi_selected_theme_test_index;

$testJsonFile = Path::join( VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $vendi_selected_theme_page, 'test', $vendi_selected_theme_page . '.test.json' );

$examples = '';
if ( ! is_readable( $testJsonFile ) ) {
    throw new Exception( 'Test file not found' );
}

$testJson = json_decode( file_get_contents( $testJsonFile ), true );
$tests    = $testJson['tests'];
if ( ! $test = $tests[ $vendi_selected_theme_test_index ] ) {
    throw new Exception( 'Test not found' );
}

if ( isset( $test['__test_template'] ) ) {
    $trueTest = $test['__test_template']['test_content'];
    if ( $urlPartsQueryString = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) {
        $urlPartsQSArray = explode( '&', $urlPartsQueryString );
        $d               = [];
        foreach ( $urlPartsQSArray as $item ) {
            $parts = explode( '=', $item );
            $d[ $parts[0] ] = $parts[1];
        }

        foreach ( [ 'content_max_width', 'content_alignment' ] as $key ) {
            if ( isset( $d[ $key ] ) ) {
                $trueTest['content_area_settings'][ $key ] = $d[ $key ];
            }
        }
    }

    $test = $trueTest;
}

global $vendi_theme_test_data;
$vendi_theme_test_data = $test;

add_filter(
    'show_admin_bar',
    static function ( $show ) {
        return false;
    }
);

?>
<!DOCTYPE html>
<html lang="en" style="margin-top: 0 !important;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <script async src="<?php echo Path::join( VENDI_CUSTOM_THEME_URL, 'vendi-theme-parts', 'templates', 'docs', 'iframe-resizer.child.js' ); ?>"></script>
    <?php wp_head() ?>
    <style>
        body {
            min-height: 0 !important;
        }
    </style>
</head>
<body>
<?php vendi_load_component_v3( $vendi_selected_theme_page ); ?>
</body>
<?php wp_footer(); ?>
</html>
