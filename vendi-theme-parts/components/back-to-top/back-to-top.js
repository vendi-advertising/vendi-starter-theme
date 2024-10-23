/* global window */
(function (w) {
    'use strict';

    const

        document = w.document,

        debounce = (fn, delay) => {
            let timer = null;
            return function () {
                const
                    context = this,
                    args = arguments
                ;
                w.clearTimeout(timer);
                timer = w.setTimeout(
                    () => {
                        fn.apply(context, args);
                    },
                    delay
                );
            };
        },

        handleScroll = () => {
            const pageOffset = document.documentElement.scrollTop || document.body.scrollTop;
            document.querySelectorAll('#back-to-top-button').forEach((button) => {
                button.classList.toggle('visible', pageOffset >= 100);
            });
        },

        run = () => {
            handleScroll();
            w.addEventListener(
                'scroll',
                () => {
                    debounce(
                        () => {
                            handleScroll();
                        },
                        100
                    )();
                }
            );
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
