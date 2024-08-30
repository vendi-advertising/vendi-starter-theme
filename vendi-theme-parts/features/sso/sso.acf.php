<?php

static $acf_mapping_sso = [
    'group_661435d8a956b.json' => 'theme-settings-sso.json',
    'group_6615bc1d65360.json' => 'theme-settings-sso-provider-github.json',
    'group_66143631ddd8b.json' => 'theme-settings-sso-provider-azure.json',
    'group_6619abc83577b.json' => 'theme-settings-sso-provider-google.json',
];

vendi_feature_register_acf( $acf_mapping_sso, __DIR__ );
