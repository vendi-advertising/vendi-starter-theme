/*global window */

(function (w) {

        'use strict'; //Force strict mode

        let minHeight = 0;

        const
            document = w.document,

            load = () => {

                const resizeObserver = new ResizeObserver(
                    (entries) => {
                        const h = entries[0].target.clientHeight;

                        if (h > minHeight) {
                            minHeight = h;
                            document.body.style.minHeight = minHeight + 'px';
                        }
                    }
                );

                resizeObserver.observe(document.body)


                document
                    .querySelectorAll('iframe')
                    .forEach(
                        (iframe) => {
                            iframeResize(
                                {
                                    license: 'GPLv3',
                                    waitForLoad: false,
                                    log: true,
                                },
                                iframe
                            );
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
        // init();
    }
)(window);
