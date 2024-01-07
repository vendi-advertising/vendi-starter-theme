/*global window */

(function (w) {

        'use strict'; //Force strict mode

        const
            document = w.document,

            accordionRoleSelector = '[data-role="accordion"]',

            load = () => {
                document
                    .querySelectorAll(`${accordionRoleSelector} [data-role="accordion-controls"] button`)
                    .forEach(
                        (button) => {
                            button
                                .addEventListener(
                                    'click',
                                    (event) => {
                                        event.preventDefault();

                                        const
                                            action = button.getAttribute('data-action'),
                                            parent = button.closest(accordionRoleSelector)
                                        ;

                                        if (!action || !parent) {
                                            return;
                                        }

                                        parent
                                            .querySelectorAll('details:not([data-always-open])')
                                            .forEach(
                                                (details) => {
                                                    details.toggleAttribute('open', action === 'expand-all');
                                                }
                                            )
                                        ;
                                    }
                                )
                            ;
                        }
                    )
                ;
            },

            init = () => {
                if (['complete', 'interactive'].includes(document.readyState)) {
                    //If the DOM is already set then invoke our load function
                    load();
                } else {
                    //Otherwise, wait for the ready event
                    document.addEventListener('DOMContentLoaded', load);
                }
            }
        ;

        //Kick everything off
        init();
    }
)(window);
