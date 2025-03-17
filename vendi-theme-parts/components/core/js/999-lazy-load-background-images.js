/*global window */

(function (w) {

        'use strict'; //Force strict mode

        const

            document = w.document,

            run = () => {

                const
                    lazyBackgrounds = [].slice.call(document.querySelectorAll('.has-lazy-background-image > .component-wrapper')),
                    lazyBackgroundObserver = new w.IntersectionObserver(
                        (entries) => {
                            entries
                                .forEach(
                                    entry => {
                                        if (entry.isIntersecting) {
                                            entry.target.dataset.visible = '';
                                            lazyBackgroundObserver.unobserve(entry.target);
                                        }
                                    }
                                )
                            ;
                        }
                    )
                ;

                lazyBackgrounds
                    .forEach(
                        (lazyBackground) => {
                            lazyBackgroundObserver.observe(lazyBackground);
                        }
                    )
                ;

            },

            init = () => {
                if (['complete', 'interactive'].includes(document.readyState)) {
                    //If the DOM is already set then invoke our load function
                    run();
                } else {
                    //Otherwise, wait for the ready event
                    document.addEventListener('DOMContentLoaded', run);
                }
            }
        ;

        //Kick everything off
        init();
    }
)(window);
