/* global window */
(function (w) {
    'use strict';

    const

        document = w.document,
        console = w.console,

        run = () => {
            document
                .querySelectorAll('[data-role="search-activation-button"][data-target-id]')
                .forEach(
                    (button) => {
                        button
                            .addEventListener(
                                'click',
                                () => {
                                    const
                                        targetId = button.getAttribute('data-target-id'),
                                        target = document.getElementById(targetId)
                                    ;
                                    if (!target) {
                                        console.debug('Search could not find target modal with ID: ' + targetId);
                                        return;
                                    }
                                    target.showModal();
                                }
                            )
                        ;
                    }
                )
            ;

            document
                .addEventListener(
                    'click',
                    (event) => {
                        if (event.target.getAttribute('data-role') === 'search-modal') {
                            event.target.close();
                        }
                    }
                )
            ;
        },

        init = () => {
            if (['complete', 'interactive'].includes(document.readyState)) {
                run();
            } else {
                document.addEventListener('DOMContentLoaded', run);
            }
        };
    init();
})(window);
