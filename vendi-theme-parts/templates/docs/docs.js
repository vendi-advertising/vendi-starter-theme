/*global window */

(function (w) {

        'use strict'; //Force strict mode

        let minHeight = 0;

        const
            document = w.document,

            $ = jQuery,

            v2 = () => {
                if (typeof acf === 'undefined' || typeof acfe === 'undefined') {
                    throw 'Could not find ACF or ACFe';
                }

                /**
                 * Init
                 */
                const flexible = acf.getFieldType('flexible_content');
                const model = flexible.prototype;

                var source = window.VENDI_DEMO.source;
                var $html = $(window.VENDI_DEMO.layouts);

                var $html_layouts = $html.closest('[data-layout]');

                if (!$html_layouts.length) {
                    return alert('No layouts data available');
                }

                flexible.$popup = () => {
                    return $(".tmpl-popup:last")
                };

                var $popup = $(flexible.$popup().html());
                var $layouts = flexible.$layouts();

                var countLayouts = function (name) {
                    return $layouts.filter(function () {
                        return $(this).data('layout') === name;
                    }).length;
                };

                // init
                var validated_layouts = [];

                // Each first level layouts
                $html_layouts.each(function () {

                    var $this = $(this);
                    var layout_name = $this.data('layout');

                    // vars
                    var $a = $popup.find('[data-layout="' + layout_name + '"]');
                    var min = $a.data('min') || 0;
                    var max = $a.data('max') || 0;
                    var count = countLayouts(layout_name);

                    // max
                    if (max && count >= max)
                        return;

                    // Validate layout against available layouts
                    var get_clone_layout = flexible.$clone($this.attr('data-layout'));

                    // Layout is invalid
                    if (!get_clone_layout.length)
                        return;

                    // Add validated layout
                    validated_layouts.push($this);

                });

                // Nothing to add
                if (!validated_layouts.length) {
                    return alert('No layouts could be pasted');
                }

                // Add layouts
                $.each(validated_layouts, function () {

                    var $layout = $(this);
                    var search = source + '[' + $layout.attr('data-id') + ']';
                    var target = flexible.$control().find('> input[type=hidden]').attr('name');

                    flexible.acfeDuplicate({
                        layout: $layout,
                        before: false,
                        search: search,
                        parent: target
                    });

                });
            },

            load = () => {

                v2();


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
        init();
    }
)(window);
