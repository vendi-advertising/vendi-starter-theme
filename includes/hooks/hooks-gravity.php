<?php

// add placeholder visibility to Gravity Forms
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

// add Gravity form confirmation anchor
add_filter( 'gform_confirmation_anchor', '__return_true' );
