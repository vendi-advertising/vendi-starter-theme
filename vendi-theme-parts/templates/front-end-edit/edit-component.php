<?php

// This is needed because we are taking over the entire save process
remove_action('acf/save_post', '_acf_do_save_post');

if (!$vendiPostId = get_query_var_int(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::POST_ID->value)) {
    throw new Exception('No post ID provided');
}

if (!$vendiFieldObject = get_field_object(VENDI_CUSTOM_THEME_COMPONENT_FIELD_NAME, $vendiPostId, load_value: false)) {
    throw new Exception('No field object found');
}

if (null === $componentId = get_query_var_int(VENDI_QUERY_STRING_FRONT_END_EDIT_KEYS::COMPONENT_ID->value)) {
    throw new Exception('No component ID provided');
}

define('VENDI_FIELD_KEY_FOR_EDIT', $vendiFieldObject['key']);
define('VENDI_GROUP_KEY_FOR_EDIT', $vendiFieldObject['parent']);

// ACF redirects with an "updated" query string, and we need to check if
// we flagged for the modal to close and pass that along so the calling JS
// to deal with
add_filter(
    'wp_redirect',
    static function ($location) {
        if (isset($_POST['__vendi-save-and-close'])) {
            $location = add_query_arg('__vendi-save-and-close', '1', $location);
        }

        return $location;
    },
);

// Pass along messages to the parent of the iframe
if ($updated = $_GET['updated'] ?? false) {
    echo '<script>window.parent.postMessage("updated", "*");</script>';
    if (isset($_GET['__vendi-save-and-close'])) {
        echo '<script>window.parent.postMessage("close-modal", "*");</script>';
    }
}

// This is where we are overriding the ACF save process
add_action(
    'acf/save_post',
    static function ($post_id) {
        $values = $_POST['acf'];

        // We embedded the remaining fields in a hidden field
        $vendiFormData = json_decode(base64_decode($_POST['vendi-form-data']), true, 512, JSON_THROW_ON_ERROR);

        // As far as I can tell, they use the row-n keys to keep track of the order
        foreach ($vendiFormData as $idx => $v) {
            $values[VENDI_FIELD_KEY_FOR_EDIT]['row-'.$idx] = $v;
        }

        // Sort array by keys. Not sure if this is necessary, but do it just in case
        ksort($values[VENDI_FIELD_KEY_FOR_EDIT]);

        // Call the native save
        acf_update_values($values, $post_id);
    },
    5,
);

acf_form_head();

add_filter('show_admin_bar', '__return_false');

add_action('wp_enqueue_scripts', function () {
    // Enqueue ACF styles and scripts
    acf_enqueue_scripts();

    // This is needed to have consistent admin styling
    wp_enqueue_style('wp-admin');
});

wp_head();

// We are only editing one component at a time, however that's not a thing in the ACF world. So we need to pretend
// that we are editing the page, then look for the specific component we want to edit by index. Everything else
// we'll stash in this variable, inject in a hidden field that we control, and finally merge it back in while saving.
$additionFormFields = null;

add_filter(
    'acf/load_value/key='.VENDI_FIELD_KEY_FOR_EDIT,
    static function ($value, $post_id, $field) use ($componentId, &$additionFormFields) {
        if (null === $additionFormFields) {
            $additionFormFields = $value;

            unset($additionFormFields[$componentId]);
        }

        return [$componentId => $value[$componentId]];
    },
    10,
    3,
);

// Cleans up the form just a bit
//  * Hide the native save button which we'll handle with our modal
//  * Remove "add component" button we don't want
//  * Give the iframe some more height
//  * Hide the layout controls except for the settings button
//    * The show/hide layout button technically works, however it causes an OOM when rendering, so I'm punting on that
//    * Because this is a single-component editor, the copy, add, remove and duplicate buttons don't make sense either
?>
    <style>
        html {
            margin-block-start: 0 !important;
        }

        body {
            margin: 0;
            padding: 0;
        }

        #acf-form > .acf-fields > .acf-field.acf-field-flexible-content > .acf-input > .acf-flexible-content > .acfe-flexible-stylised-button:has([data-name="add-layout"]),
        #acf-form > .acf-fields > .acf-field.acf-field-flexible-content > .acf-label {
            display: none;
        }

        .acfe-fc-placeholder iframe {
            height: 100% !important;
        }

        #acf-form .acf-form-submit [type="submit"] {
            visibility: hidden;
        }

        #acf-form .acf-fc-layout-controls > *:not([data-acfe-flexible-settings]) {
            display: none;
        }

        /* The settings button is just slight off. Not sure if this is a theme/admin problem, or in ACF, but the fix is simple */
        #acf-form .acf-fc-layout-controls > [data-acfe-flexible-settings]::before {
            line-height: 1.2;
        }
    </style>
<?php

acf_form([
    'post_id' => $vendiPostId,
    'field_groups' => [VENDI_GROUP_KEY_FOR_EDIT],
    'submit_value' => 'Save',
    'form' => true,
]);

?>
    <script>

        const forms = document.querySelectorAll('form');
        if (forms.length !== 1) {
            alert('Wrong number of forms');
        } else {
            const form = forms[0];
            const newField = document.createElement('input');
            const submitButton = form.querySelector('[type="submit"]');
            newField.type = 'hidden';
            newField.name = 'vendi-form-data';
            newField.value = '<?php echo base64_encode(json_encode($additionFormFields)); ?>';

            form.append(newField);

            window.onmessage = function (e) {
                const save = e.data === 'save' || e.data === 'save-and-close';
                if (e.data === 'save-and-close') {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '__vendi-save-and-close';
                    input.value = '1';
                    form.append(input);
                }
                if (save) {
                    submitButton.click();
                }
            };
        }
    </script>
<?php


wp_footer();
