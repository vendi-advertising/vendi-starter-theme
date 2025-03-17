<?php

// This is our magic path to flag that we are in front end edit mode
const VENDI_PATH_ROOT_THEME_FRONT_END_EDIT = '__vendi-front-end-edit';

// Query string keys that we might need
enum VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS: string
{
    // This key is a flag-key only. We use it to say that we are in front end edit mode, but otherwise it has no value
    case FRONT_END_EDIT_MODE = 'vendi-front-end-edit-mode';
    case POST_ID             = 'vendi-post-id';
    case POST_TYPE           = 'vendi-post-type';
    case COMPONENT_ID        = 'vendi-component-id';
    case ACTION              = 'vendi-action';
    case ACTION_ID           = 'vendi-action-id';
}

add_action(
    'init',
    static function () {
        add_rewrite_rule('^' . VENDI_PATH_ROOT_THEME_FRONT_END_EDIT . '/?$', 'index.php?' . VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::FRONT_END_EDIT_MODE->value . '=index', 'top');
    },
);

add_filter(
    'query_vars',
    static function ($query_vars) {
        foreach (VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::cases() as $key) {
            $query_vars[] = $key->value;
        }

        return $query_vars;
    },
);

if ( ! function_exists('get_query_var_int')) {
    function get_query_var_int(string $key): ?int
    {
        $value = get_query_var($key);
        if ( ! ctype_digit($value)) {
            return null;
        }

        return (int)$value;
    }
}


add_filter(
    'template_include',
    static function ($template) {
        if (get_query_var(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::FRONT_END_EDIT_MODE->value)) {
            switch(get_query_var(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::ACTION->value)) {
                case 'edit':
                    return vendi_maybe_get_template_name('front-end-edit', filename: 'edit-component.php');
                case 'render':
                    return vendi_maybe_get_template_name('front-end-edit', filename: 'render-component.php');
            }
        }

        return $template;
    },
);

