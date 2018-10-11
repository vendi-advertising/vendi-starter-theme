/*jslint white: true, browser: true, plusplus: true, esversion: 6*/

/*global window, document*/

(function() {

    'use strict'; //Force strict mode

    const

        MAGIC_ATTRIBUTE_FOR_ROLE            = 'data-role',
        MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED    = 'data-js-parallax-speed',

        MAGIC_DATA_ROLE_WINDOW              = 'js-parallax-window',
        MAGIC_DATA_ROLE_BACKGROUND          = 'js-parallax-background',

        LEGACY_ID_WINDOW                    = 'js-parallax-window',
        LEGACY_ID_BACKGROUND                = 'js-parallax-background',

        //CSS selector stuff
        CSS_SELECTOR_CONTAINS_WORD  = '~=',
        CSS_LEFT_SQUARE_BRACKET     = '[',
        CSS_RIGHT_SQUARE_BRACKET    = ']',

        //Shortcuts for the above
        CW = CSS_SELECTOR_CONTAINS_WORD,
        LB = CSS_LEFT_SQUARE_BRACKET,
        RB = CSS_RIGHT_SQUARE_BRACKET,

        //Shortcuts for full selectors
        MAGIC_SELECTOR_FOR_PARALLAX_WINDOW     = LB + MAGIC_ATTRIBUTE_FOR_ROLE + CW + MAGIC_DATA_ROLE_WINDOW     + RB,
        MAGIC_SELECTOR_FOR_PARALLAX_BACKGROUND = LB + MAGIC_ATTRIBUTE_FOR_ROLE + CW + MAGIC_DATA_ROLE_BACKGROUND + RB,

        MAGIC_SELECTOR_FOR_PARALLAX_WINDOW_OLD_WAY      = '#' + LEGACY_ID_WINDOW     + ':not(' + LB + MAGIC_ATTRIBUTE_FOR_ROLE + CW + MAGIC_DATA_ROLE_WINDOW     + RB + ')',
        MAGIC_SELECTOR_FOR_PARALLAX_BACKGROUND_OLD_WAY  = '#' + LEGACY_ID_BACKGROUND + ':not(' + LB + MAGIC_ATTRIBUTE_FOR_ROLE + CW + MAGIC_DATA_ROLE_BACKGROUND + RB + ')',

        SCROLL_SPEED_IF_NOT_SET_IN_HTML     = 0.35,

        get_js_parallax_windows = () => {

            const
                //Get all with a data-role="js-parallax-window" and convert from NodeList to array
                new_way = Array.prototype.slice.call(document.querySelectorAll(MAGIC_SELECTOR_FOR_PARALLAX_WINDOW)),

                //Get all with an ID of js-parallax-window except those with a data-role="js-parallax-window" and convert from NodeList to array
                old_way = Array.prototype.slice.call(document.querySelectorAll(MAGIC_SELECTOR_FOR_PARALLAX_WINDOW_OLD_WAY)),
                merged = Array.prototype.concat.apply([], [new_way, old_way])
            ;

            if(old_way.length){
                console.log('Legacy parallax ID found, please upgrade to data roles');
            }

            return merged;
        },

        get_js_parallax_background_for_window = (plxWindow) => {
            var
                //Get all with a data-role="js-parallax-background" and convert from NodeList to array
                new_way = Array.prototype.slice.call(plxWindow.querySelectorAll(MAGIC_SELECTOR_FOR_PARALLAX_BACKGROUND)),

                //Get all with an ID of js-parallax-background except those with a data-role="js-parallax-background" and convert from NodeList to array
                old_way = Array.prototype.slice.call(plxWindow.querySelectorAll(MAGIC_SELECTOR_FOR_PARALLAX_BACKGROUND_OLD_WAY)),
                merged = Array.prototype.concat.apply([], [new_way, old_way])
            ;

            if(old_way.length){
                console.log('Legacy parallax ID found, please upgrade to data roles');
            }

            if(merged.length > 0){
                return merged.shift();
            }

            return null;
        },

        parallax = () => {
            get_js_parallax_windows()
                .forEach(
                    (parallax_window) => {

                        const
                            background_image = get_js_parallax_background_for_window(parallax_window),
                            distance_to_top = parallax_window.offsetTop - window.pageYOffset,
                            speed = parallax_window.hasAttribute(MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED) ? parseFloat(parallax_window.getAttribute(MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED)) : SCROLL_SPEED_IF_NOT_SET_IN_HTML,
                            px = - ( distance_to_top * speed),
                            px_as_string = px + 'px'
                        ;

                        background_image.style.top = px_as_string;

                    }
                )
            ;
        },

        load = () => {
            //Parallax no matter what, just to kick things off
            parallax();

            //Parallax on scroll.
            //NOTE: We are not using a debounce here because we need the
            //parallax to fire often enough to be smooth.
            window .addEventListener( 'scroll', parallax );
        },

        init = () =>  {
            if(['complete', 'loaded', 'interactive'].includes(document.readyState)){
                //If the DOM is already set, then just load
                load();
            }else{
                //Otherwise, wait for the readevent
                document.addEventListener('DOMContentLoaded', load);
            }
        }

    ;

    //Kick everything off
    init();
}
()
);
