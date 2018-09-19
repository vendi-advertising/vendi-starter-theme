/*jslint white: true, browser: true, plusplus: true*/

/*global window, document*/

(function() {
    'use strict'; //Force strict mode

    var

        MAGIC_ATTRIBUTE_FOR_ROLE            = 'data-role',
        MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED    = 'data-js-parallax-speed',

        MAGIC_DATA_ROLE_WINDOW              = 'js-parallax-window',
        MAGIC_DATA_ROLE_BACKGROUND          = 'js-parallax-background',

        LEGACY_ID_WINDOW                    = 'js-parallax-window',
        LEGACY_ID_BACKGROUND                = 'js-parallax-background',

        SCROLL_SPEED_IF_NOT_SET_IN_HTML     = 0.35,

        debounce = function(func) {
            var
                wait = arguments.length <= 1 || arguments[1] === undefined ? 100 : arguments[1],
                timeout = void 0
            ;

            return function () {
                var
                    _this = this,
                    _len,
                    args,
                    _key
                ;

                for(_len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
                    args[_key] = arguments[_key];
                }

                clearTimeout(timeout);
                timeout = setTimeout(
                            function () {
                                func.apply(_this, args);
                            },
                            wait
                        );
            };
        },

        do_parallax = function(){
            var
                js_parallax_windows = get_js_parallax_windows(),
                i
            ;

            for(i = 0; i < js_parallax_windows.length; i++){

                var
                    plxWindow = js_parallax_windows[i],
                    plxBackground = get_js_parallax_background_for_window(plxWindow)
                ;

                if(!plxBackground){
                    continue;
                }

                var
                    plxWindowTopToPageTop = plxWindow.offsetTop,
                    windowTopToPageTop = window.scrollTop,
                    plxWindowTopToWindowTop = plxWindowTopToPageTop - windowTopToPageTop,

                    plxSpeed = plxWindow.hasAttribute(MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED) ? parseFloat(plxWindow.getAttribute(MAGIC_ATTRIBUTE_FOR_SCROLL_SPEED)) : SCROLL_SPEED_IF_NOT_SET_IN_HTML

                ;

                plxBackground.style.top = '-' + (plxWindowTopToWindowTop * plxSpeed) + 'px';

            }
        },

        parallax = function(){
            debounce(do_parallax);
        },

        has_parallax_window = function(){
            return get_js_parallax_windows().length > 0;
        },

        get_js_parallax_background_for_window = function(plxWindow){
            var
                //Get all with a data-role="js-parallax-background" and convert from NodeList to array
                new_way = Array.prototype.slice.call(plxWindow.querySelectorAll('[' + MAGIC_ATTRIBUTE_FOR_ROLE + '~=' + MAGIC_DATA_ROLE_BACKGROUND + ']')),

                //Get all with an ID of js-parallax-background except those with a data-role="js-parallax-background" and convert from NodeList to array
                old_way = Array.prototype.slice.call(plxWindow.querySelectorAll('#' + LEGACY_ID_BACKGROUND + ':not([' + MAGIC_ATTRIBUTE_FOR_ROLE + '~=' + MAGIC_DATA_ROLE_BACKGROUND + '])')),
                merged = Array.prototype.concat.apply([], [new_way, old_way])
            ;

            if(merged.length > 0){
                return merged.shift();
            }

            return null;
        },

        get_js_parallax_windows = function(){
            var
                //Get all with a data-role="js-parallax-window" and convert from NodeList to array
                new_way = Array.prototype.slice.call(document.querySelectorAll('[' + MAGIC_ATTRIBUTE_FOR_ROLE + '~=' + MAGIC_DATA_ROLE_WINDOW + ']')),

                //Get all with an ID of js-parallax-window except those with a data-role="js-parallax-window" and convert from NodeList to array
                old_way = Array.prototype.slice.call(document.querySelectorAll('#' + LEGACY_ID_WINDOW + ':not([' + MAGIC_ATTRIBUTE_FOR_ROLE + '~=' + MAGIC_DATA_ROLE_WINDOW + '])')),
                merged = Array.prototype.concat.apply([], [new_way, old_way])
            ;

            return merged;
        },

        init = function(){
            if(has_parallax_window()){
                document.addEventListener('DOMContentLoaded', parallax);
                document.addEventListener('scroll', parallax);
            }
        }

    ;

    //Kick everything off
    init();
}()
);
