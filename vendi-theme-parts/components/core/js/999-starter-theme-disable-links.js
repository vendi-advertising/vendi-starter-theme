/*global window */

(function (w) {

        'use strict'; //Force strict mode

        const
            document = w.document,
            load = () => {
                document
                    .querySelectorAll('[data-disable-links]')
                    .forEach(
                        (el) => {
                            el
                                .addEventListener(
                                    'click',
                                    (e) => {
                                        e.preventDefault();
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
