<?php

use Symfony\Component\Filesystem\Path;
use Vendi\Theme\SsoRouter;

const VENDI_QUERY_STRING_KEY_SSO = 'vendi-sso';

add_action(
    'init',
    static function () {
        $path = ltrim(SsoRouter::VENDI_PATH_SSO_ROOT, '/');

        // The specific query string value is not important, just that it is present
        add_rewrite_rule('^'.$path.'[/]?', 'index.php?'.VENDI_QUERY_STRING_KEY_SSO.'=true', 'top');
    }
);

add_filter(
    'query_vars',
    static function ($query_vars) {
        $query_vars[] = VENDI_QUERY_STRING_KEY_SSO;

        return $query_vars;
    }
);

add_filter(
    'template_include',
    static function ($template) {
        if (get_query_var(VENDI_QUERY_STRING_KEY_SSO)) {

            if (!defined('VENDI_PERFORMING_ACTION_SSO')) {
                define('VENDI_PERFORMING_ACTION_SSO', true);
            }

            session_start();

            // The path must be absolute
            return VENDI_CUSTOM_THEME_PATH.'/router/router.php';
        }

        return $template;
    }
);

add_action(
    'login_enqueue_scripts',
    static function () {

        $sso_providers = get_field('sso_providers', 'option');
        if (!$sso_providers) {
            return;
        }

        $providerImages = [];

        foreach ($sso_providers as $provider) {
            if ('azure_provider' === $provider['acf_fc_layout']) {
                $providerImages['azure'] = Path::join(VENDI_CUSTOM_THEME_URL, 'images', 'sso', 'azure.svg');
            }
        }

        if (empty($providerImages)) {
            return;
        }

        vendi_theme_enqueue_script('vendi-sso', '/js/sso.js');
        wp_localize_script(
            'vendi-sso',
            'VENDI_SSO',
            [
                'images' => $providerImages,
                'lookupUrl' => SsoRouter::VENDI_PATH_SSO_LOOKUP,
                'strings' => [
                    'email' => __('Email Address'),
                ],
            ]
        );

        vendi_theme_enqueue_style('vendi-sso', '/css/sso.css');
    }
);

