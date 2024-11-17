<?php

use Symfony\Component\Filesystem\Path;

add_action(
    "acfe/flexible/render/before_template",
    static function ($field, $layout, $is_preview) {
        if ( ! $is_preview) {
            return;
        }

        static $supportedLayouts = [
            'hero'               => [
                'hero-single',
            ],
            'content_components' => [
                'accordion',
                'basic_copy',
                'blockquote',
                'callout',
                'columns',
                'figure',
                'form',
                'group',
                'image_gallery',
                'showcase',
                'stats',
                'stepwise',
                'testimonial',
            ],
        ];

        $thisFieldName  = $field['name'];
        $thisLayoutName = $layout['name'];

        if ( ! array_key_exists($thisFieldName, $supportedLayouts)) {
            return;
        }

        if ( ! in_array($thisLayoutName, $supportedLayouts[$thisFieldName], true)) {
            return;
        }

        /*
         * We need to load per-component CSS, but we also don't want to change the render system
         * to test whether it is in preview mode or not. So we'll continue to enqueue styles
         * normally, but we'll use a backup copy of the global $wp_styles to get the files so
         * that we can reset it each time. We're also going to load from disk instead of from
         * URL in order to reduce the number of HTTP requests.
         *
         * NOTE: For anything that registers in one pass, and then enqueues in another pass, this
         * could break. It should be pretty easy to clone the registered styles over, however, or
         * clone the entire object, and just reset the queue.
         */

        global $wp_styles;
        // Backup the global $wp_styles and replace it with a fresh copy
        $backup_wp_styles = $wp_styles;
        $wp_styles        = new WP_Styles();

        vendi_load_component_v3('core');

        /*
         * In order to not have the component be aware that it is rendering in preview mode, load it normally but buffer it.
         *
         * NOTE: Each component system needs to be manually handled here for now.
         */
        ob_start();
        if ('content_components' === $thisFieldName) {
            vendi_load_component_v3($layout['name']);
        } elseif ('hero' === $thisFieldName) {
            // This is done to load some required classes
            ob_start();
            vendi_load_component_v3('hero');
            ob_get_clean();
            vendi_load_component_v3('hero/hero-single');
        }
        $html = ob_get_clean();

        // Grab the individual CSS files
        $cssText = [];
        foreach ($wp_styles->queue as $handle) {
            if ( ! array_key_exists($handle, $wp_styles->registered)) {
                continue;
            }

            if ( ! $url = $wp_styles->registered[$handle]->src) {
                continue;
            }

            if (is_bool($url)) {
                continue;
            }

            if ( ! $path = parse_url($url, PHP_URL_PATH)) {
                continue;
            }

            $absolutePath = Path::join(ABSPATH, $path);
            if ( ! is_readable($absolutePath)) {
                continue;
            }

            $cssText[] = file_get_contents($absolutePath);
        }

        // Restore the global $wp_styles
        $wp_styles = $backup_wp_styles;

        $cssText = implode("\n", $cssText);
        $html    = "<style>{$cssText}</style>{$html}";

        // This is used to guarantee that the iframe has a unique ID relative to the JavaScript
        $randomIframeId = '_' . md5($html);
        ?>
        <style>
            .acfe-fc-placeholder.acfe-fc-preview <?php echo '#' . $randomIframeId; ?> {
                width: 100%;
                height: 150px;
                border: none;
                transition: height 250ms ease-in-out;
            }

            .acfe-fc-placeholder.acfe-fc-preview:hover <?php echo '#' . $randomIframeId; ?> {
                height: 50dvh;
            }
        </style>
        <iframe id="<?php echo $randomIframeId; ?>"></iframe>
        <script>
            (function () {

                const
                    getOrCreateDialog = (parent) => {
                        let dialog = parent.querySelector('dialog');
                        if (dialog) {
                            dialog.innerHTML = '';
                            return dialog;
                        }

                        dialog = document.createElement('dialog');
                        dialog.style.width = '90dvw';
                        dialog.style.height = '90dvh';
                        dialog.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                        });
                        parent.appendChild(dialog);
                        return dialog;
                    },

                    createDialogHeader = () => {
                        const dialogHeader = document.createElement('header');
                        dialogHeader.style.display = 'grid';
                        dialogHeader.style.backgroundColor = 'black';
                        dialogHeader.style.padding = '10px';
                        dialogHeader.style.gridTemplateColumns = '1fr 40px';

                        return dialogHeader;
                    },

                    createDialogH2 = () => {
                        const dialogH2 = document.createElement('h2');
                        dialogH2.textContent = 'Preview';
                        dialogH2.style.color = 'white';
                        dialogH2.style.fontSize = '1.5rem';
                        dialogH2.style.fontWeight = 'bold';

                        return dialogH2;
                    },

                    createIframeWrapper = () => {
                        const wrapper = document.createElement('div');
                        wrapper.style.display = 'grid';
                        wrapper.style.gridTemplateRows = 'min-content 1fr';
                        wrapper.style.height = '100%';

                        return wrapper;
                    },

                    createClickTrap = () => {
                        const clickTrap = document.createElement('div');
                        clickTrap.style.cursor = 'not-allowed';
                        clickTrap.style.zIndex = '1000';
                        clickTrap.style.gridRow = '2';
                        clickTrap.style.gridColumn = '1';

                        return clickTrap;
                    },

                    createIframeForDialog = () => {
                        const iframe = document.createElement('iframe');
                        iframe.style.width = '100%';
                        iframe.style.height = '100%';
                        iframe.style.border = 'none';
                        iframe.style.gridRow = '2';
                        iframe.style.gridColumn = '1';

                        return iframe;
                    },

                    createDialogCloseButton = (dialog) => {
                        const dialogCloseButton = document.createElement('button');
                        dialogCloseButton.textContent = 'X';
                        dialogCloseButton.style.backgroundColor = 'black';
                        dialogCloseButton.style.color = 'white';
                        dialogCloseButton.style.border = 'none';
                        dialogCloseButton.style.fontSize = '1.5rem';
                        dialogCloseButton.style.fontWeight = 'bold';
                        dialogCloseButton.style.padding = '10px';
                        dialogCloseButton.style.cursor = 'pointer';
                        dialogCloseButton.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            dialog.close();
                        });

                        return dialogCloseButton;
                    },

                    getOrCreateIframe = (dialog) => {
                        let iframe = dialog.querySelector('iframe');
                        if (iframe) {
                            return iframe;
                        }

                        iframe = createIframeForDialog();

                        const
                            clickTrap = createClickTrap(),
                            wrapper = createIframeWrapper(),
                            dialogHeader = createDialogHeader(),
                            dialogH2 = createDialogH2(),
                            dialogCloseButton = createDialogCloseButton(dialog)
                        ;

                        dialogHeader.appendChild(dialogH2);
                        dialogHeader.appendChild(dialogCloseButton);
                        wrapper.append(dialogHeader, iframe, clickTrap);
                        dialog.appendChild(wrapper);

                        return iframe;
                    },

                    getOrCreateButton = (parent, dialog) => {

                        let button = parent.querySelector('button');
                        if (button) {
                            return button;
                        }

                        const span = document.createElement('span');
                        span.classList.add('dashicons', 'dashicons-visibility') //'<span class=" "></span>');

                        button = document.createElement('a');
                        button.classList.add('button');

                        button.addEventListener('click', (e) => {

                            e.preventDefault();
                            e.stopPropagation();
                            const iframe2 = getOrCreateIframe(dialog);

                            iframe2.contentWindow.document.open();
                            iframe2.contentWindow.document.write(html);
                            iframe2.contentWindow.document.close();


                            dialog.showModal();
                        });
                        button.style.transform = 'translateX(20px)  translateY(-14px)';
                        button.appendChild(span);

                        return button;
                    }
                ;

                const
                    randomIframeId = <?php echo json_encode($randomIframeId, JSON_THROW_ON_ERROR); ?>,
                    html = <?php echo json_encode($html, JSON_THROW_ON_ERROR); ?>,
                    iframe = document.getElementById(randomIframeId),
                    parent = iframe.closest('.acfe-fc-preview'),
                    dialog = getOrCreateDialog(parent)
                ;


                const button = getOrCreateButton(parent, dialog);

                parent.appendChild(button);
                parent.appendChild(dialog);

                iframe.src = "about:blank";
                iframe.contentWindow.document.open();
                iframe.contentWindow.document.write(html);
                iframe.contentWindow.document.close();
            })();
        </script>
        <?php
    },
    accepted_args: 3,
);
