/*jslint maxparams: 4, maxdepth: 4, maxstatements: 20, maxcomplexity: 8 */
(function() {
    'use strict'; //Force strict mode
    
    let
        useLegacyClasslist = null
    ;

    const
        /*
        NOTE: This code assumes that your HTML looks something like:
        <div data-hover-and-click>
        which is equivilent to:
        <div data-hover-and-click="hover">
        However, MAGIC_SELECTOR and MAGIC_ATTRIBUTE_NAME can be changed such as:
        MAGIC_ATTRIBUTE_NAME = 'data-whatever-you-want';
        MAGIC_SELECTOR = '.custom-class-name';
        allowing you to use:
        <div class="custom-class-name" data-whatever-you-want="custom-hover-class">
        which upon hover/click will look like:
        <div class="custom-class-name custom-hover-class" data-whatever-you-want="custom-hover-class">
         */

        //See the notes above for a description of these three attributes
        MAGIC_ATTRIBUTE_NAME = 'data-hover-and-click',

        MAGIC_SELECTOR = '[' + MAGIC_ATTRIBUTE_NAME + ']',

        DEFAULT_CLASS_NAME = 'hover',

        /**
         * Detects whether the HTML node has the supplied CSS class.
         *
         * @param  {Object}  element   The HTML node to test.
         * @param  {String}  className The class to look for.
         * @return {Boolean}           True if the element is valid and the class is found, otherwise false.
         */
        hasClass = function( element, className )
        {
            if( ! element )
            {
                return;
            }

            if( ! useLegacyClasslist )
            {
                return element.classList.contains(className);
            }

            if( ! element.className )
            {
                return false;
            }

            //Trick for handling missing classes or just single classes, just surround everything with whitespace
            return ( ' ' + element.className + ' ' ).indexOf( ' ' + className + ' ' ) > -1;
        },

        /**
         * Remove the supplied CSS class from the given HTML node.
         * @param  {Object} element   The HTML node to test.
         * @param  {String} className The class to look for.
         */
        removeClass = function( element, className )
        {
            //Bail early
            if( ! element )
            {
                return;
            }

            //Upgrade to modern if possible
            if( ! useLegacyClasslist )
            {
                element.classList.remove(className);
                return;
            }

            //Legacy/IE9 and less
            if( ! hasClass( element, className ) )
            {
                return;
            }

            element.className = ( ' ' + element.className + ' ' ).replace( ' ' + className + ' ', ' ' ).replace( /\s+/g, ' ' );
        },

        /**
         * Add the supplied CSS class to the given HTML node.
         *
         * @param {Object} element   The HTML node to test.
         * @param {String} className The class to look for.
         */
        addClass = function( element, className )
        {
            //Bail early
            if( ! element )
            {
                return;
            }

            //Upgrade to modern if possible
            if( ! useLegacyClasslist )
            {
                element.classList.add(className);
                return;
            }

            //Legacy/IE9 and less
            if( hasClass( element, className ) )
            {
                return;
            }

            //Append the class and remove any extra whitespace
            element.className += ' ' + className;
            element.className = element.className.replace( /\s+/g, ' ' );
        },

        /**
         * Attempt at getting the custom class name to use, otherwise return the
         * default
         * @param  {Object} element The HTML node to get the attribute from.
         */
        getClassNameForHover = function(element)
        {
            return element && element.getAttribute(MAGIC_ATTRIBUTE_NAME) || DEFAULT_CLASS_NAME;
        },

        handleHoverClick = function(element)
        {
            addClass(element, getClassNameForHover(element));
        },

        handleMouseOut = function(element)
        {
            removeClass(element, getClassNameForHover(element));
        },

        createHandleHoverClickFunction = function(element)
        {
            return  function()
                    {
                        return handleHoverClick(element);
                    };
        },

        createHandleMouseOutFunction = function(element)
        {
            return  function()
                    {
                        return handleMouseOut(element);
                    };
        },

        load = function()
        {
            var
                //Grab all elements with the magic selector for hover
                elements = document.querySelectorAll(MAGIC_SELECTOR),
                element,
                i
            ;

            for(i = 0; i < elements.length; i++)
            {
                //Shortcut
                element = elements[ i ];

                //Bind listeners. Use function-creators to make Douglas
                //Crockford happy and to avoid messy finding of actual targets
                //later.
                element.addEventListener( 'click',     createHandleHoverClickFunction( element ) );
                element.addEventListener( 'mouseover', createHandleHoverClickFunction( element ) );
                element.addEventListener( 'mouseout',  createHandleMouseOutFunction( element ) );
            }
        },

        compatCheck = function()
        {
            useLegacyClasslist =  ! ('classList' in document.createElement("_") );
        },

        init = function()
        {
            //IE7 and less doesn't support QSA. There's a polyfill but the site
            //probably doesn't support it either so not worth it here.
            if(!document.querySelectorAll){
                console.log('QSA not supported... UX downgraded... exiting');
                return;
            }

            compatCheck();

            //Delay running a bit
            document.addEventListener('DOMContentLoaded', load);
        }

    ;

    //Kick everything off
    init();
}
()
);
