<?php

static $acf_mapping_alerts = [
    'group_65f357e01af5b'          => 'entity-alert-fields.json',
    'post_type_65f3571fbc97e.json' => 'entity-alert.json',
];

vendi_feature_register_acf( $acf_mapping_alerts, __DIR__ );
