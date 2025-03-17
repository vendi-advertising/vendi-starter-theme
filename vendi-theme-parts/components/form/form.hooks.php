<?php

add_filter(
    'gform_submit_button',
    static function (string $button, array $form): string {
        $p = new WP_HTML_Tag_Processor($button);
        if ($p->next_tag()) {
            $classes = ['call-to-action', 'call-to-action-button', 'primary', 'normal', 'dark', /*'gform-theme-no-framework'*/ /*, 'gform-theme__disable-framework'*/];
            foreach ($classes as $class) {
                $p->add_class($class);
            }

            $classes = ['gform_button', 'button'];
            foreach ($classes as $class) {
                $p->remove_class($class);
            }

            $button = $p->get_updated_html();
        }

        return $button;
    },
    accepted_args: 2,
);
