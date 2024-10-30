<?php
if (defined('WP_CLI') && WP_CLI) {
    class ACF_Generate_Component_Documentation_Command {

        /**
         * This is a tool to generate documentation of a specified component
         *
         * ## OPTIONS
         *
         * <json_file_path>
         * * : The path to the ACF JSON file.
         *
         * ## EXAMPLES
         *
         *     wp vendi document jsonFileName mdFileName
         *
         * @param array $args Positional arguments.
         * @param array $assoc_args Associative arguments.
         */
        public function document( $componentFile,  $documentName) : void{
            $filePath = VENDI_CUSTOM_THEME_PATH . '/.acf-json/components/' . $componentFile[0] . '.json';
            if (!file_exists($filePath)) {
                WP_CLI::error('File does not exist');
                return;
            }
            $file = json_decode(file_get_contents($filePath));
            $file = [$file];
            $fields = $file[0]->fields;
            // Start building the markdown content
            $markdown_content = "# ACF Fields Export\n\n";

            foreach ($fields as $field) {
                if ($field->type === 'acfe_column') {
                    continue;
                }

                //Group
                if ($field->type === 'tab') {
                    $markdown_content .= "## Tab: " . ($field->label ?? 'N/A') . "\n";
                }

                else {
                    $markdown_content .= "### Field: " . ($field->label ?? 'N/A') . "\n";
                    $markdown_content .= "- **Type**: " . ($field->type ?? 'N/A') . "\n";
                    $markdown_content .= "- **Instructions**: " . ($field->instructions ?? 'N/A') . "\n";
                    $markdown_content .= "- **Required**: " . (isset($field->required) && $field->required ? 'Yes' : 'No') . "\n";
                    $markdown_content .= "\n";
                }



            }
            $markdown_file_path = VENDI_CUSTOM_THEME_PATH . '/.docs/components/' . $componentFile[0] . '.md';
            // Write to the markdown file
            if (file_put_contents($markdown_file_path, $markdown_content) !== false) {
                WP_CLI::success("Fields have been successfully written to {$markdown_file_path}");
            } else {
                WP_CLI::error("Could not write to markdown file at {$markdown_file_path}");
            }
        }
    }

    // Register the custom command with WP-CLI
    WP_CLI::add_command('vendi', 'ACF_Generate_Component_Documentation_Command');
}
