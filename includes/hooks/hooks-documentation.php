<?php

// The specific query string value is not important, just that it is present
const VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION = 'vendi-theme-documentation';
const VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION_TEST_INDEX = 'vendi-theme-documentation-test-index';
const VENDI_PATH_ROOT_THEME_DOCUMENTATION = '__theme_docs';

add_action(
    'init',
    static function () {
        add_rewrite_rule( '^' . VENDI_PATH_ROOT_THEME_DOCUMENTATION . '/([^/]+)/test/([0-9]+)/?$', 'index.php?' . VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION . '=$matches[1]&' . VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION_TEST_INDEX . '=$matches[2]', 'top' );
        add_rewrite_rule( '^' . VENDI_PATH_ROOT_THEME_DOCUMENTATION . '/([^/]+)/?$', 'index.php?' . VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION . '=$matches[1]', 'top' );
        add_rewrite_rule( '^' . VENDI_PATH_ROOT_THEME_DOCUMENTATION . '/?$', 'index.php?' . VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION . '=index', 'top' );
    }
);

add_filter(
    'query_vars',
    static function ( $query_vars ) {
        $query_vars[] = VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION;
        $query_vars[] = VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION_TEST_INDEX;

        return $query_vars;
    }
);

add_filter(
    'template_include',
    static function ( $template ) {
        if ( $page = get_query_var( VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION ) ) {
            global $vendi_selected_theme_page;
            $vendi_selected_theme_page = $page;
            global $vendi_selected_theme_test_index;

            $vendi_selected_theme_test_index = null;

            $testIndex = get_query_var( VENDI_QUERY_STRING_KEY_THEME_DOCUMENTATION_TEST_INDEX, null );
            if ( is_string( $testIndex ) && is_numeric( $testIndex ) ) {
                global $vendi_theme_test_mode;
                $vendi_theme_test_mode = true;

                $vendi_selected_theme_test_index = $testIndex;

                return _vendi_maybe_get_template_name( 'docs', filename: 'test.php' );
            }
            return _vendi_maybe_get_template_name( 'docs', filename: 'index.php' );
        }

        return $template;
    }
);
