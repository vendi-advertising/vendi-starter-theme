/* global window */
(function (w) {
    const
        document = w.document,

        openItemById = function (id) {
            const target = document.getElementById(id);
            if (!target) {
                return;
            }

            // This allows linking within an accordion item, too
            if (target.nodeName === "DETAILS" || target.closest('details')) {
                target.open = true;
                target.scrollIntoView();
            }
        },

        //NOTE: This is for linking to accordions on the same page
        assignLinkEventListener = () => {
            document.querySelectorAll('a').forEach((link) => {
                if (link.href) {
                    const hash = new w.URL(link.href).hash.substring(1);
                    if (!hash) {
                        return;
                    }
                    const target = document.getElementById(hash);
                    if (!target) {
                        return;
                    }
                    link.addEventListener('click', () => {
                        openItemById(hash);
                    });
                }
            });
        },

        lookForPageLoadHash = function () {
            const hash = w.location.hash.substring(1);
            if (!hash) {
                return;
            }

            openItemById(hash);

        },

        load = function () {
            assignLinkEventListener();
            lookForPageLoadHash();
        },

        init = function () {
            if (['complete', 'interactive'].indexOf(document.readyState) >= 0) {
                // If the DOM is already set, then load
                load();
            } else {
                //Otherwise, wait for the ready event
                document.addEventListener('DOMContentLoaded', load);
            }
        }
    ;

    init();

}(window));
