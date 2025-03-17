/*global window */

(function (w) {

        'use strict'; //Force strict mode

        class VendiCallToAction extends HTMLElement {
            constructor() {
                super();

                // const shadow = this.attachShadow({mode: 'open'});
                // this.anchor = document.createElement('a');
                // shadow.appendChild(this.anchor);
            }

            //
            // static get observedAttributes() {
            //     // Observe all common anchor tag attributes
            //     return [
            //         'href',
            //         'target',
            //         'rel',
            //         'class',
            //     ];
            // }
            //
            // attributeChangedCallback(name, oldValue, newValue) {
            //     // Reflect attributes to the anchor tag
            //     if (this.anchor) {
            //         this.anchor.setAttribute(name, newValue);
            //     }
            // }
            //
            // connectedCallback() {
            //     // Reflect inner content if provided
            //     if (!this.anchor.innerHTML) {
            //         this.anchor.innerHTML = this.innerHTML;
            //     }
            // }
        }

        w.customElements.define('vendi-call-to-action', VendiCallToAction);

        const
            document = w.document,
            console = w.console,

            processButtonAreaClick = (buttonArea) => {

                const
                    //.acf-fields
                    previewArea = buttonArea.querySelector('& > .acf-fields > [data-key=field_678183335c23b] .acf-input'),
                    buttonKindArea = buttonArea.querySelector('& > .acf-fields > [data-name=button_settings] [data-name=button_kind]'),
                    buttonStyleArea = buttonArea.querySelector('& > .acf-fields > [data-name=button_settings] [data-name=button_style]'),
                    buttonColorArea = buttonArea.querySelector('& > .acf-fields > [data-name=button_settings] [data-name=button_color]')
                ;

                if (!previewArea) {
                    throw 'Could not find preview area for buttons';
                }

                if (!buttonKindArea) {
                    throw 'Could not find area for button kinds';
                }

                if (!buttonStyleArea) {
                    throw 'Could not find area for button styles';
                }

                if (!buttonColorArea) {
                    throw 'Could not find area for button colors';
                }

                const
                    selectedButtonKind = buttonKindArea.querySelector('input[type=radio]:checked'),
                    selectedButtonStyle = buttonStyleArea.querySelector('input[type=radio]:checked'),
                    selectedButtonColor = buttonColorArea.querySelector('input[type=radio]:checked')
                ;

                const backgroundColors = [
                    'pink',
                    'lightblue',
                    'darkgreen',
                    'darkblue',
                ];

                const newButtons = [];

                backgroundColors
                    .forEach(
                        (color) => {
                            const previewBox = document.createElement('div');
                            const previewButton = document.createElement('vendi-call-to-action');
                            previewButton.classList.add('call-to-action', 'call-to-action-button', selectedButtonKind.value, selectedButtonStyle.value, selectedButtonColor.value);
                            previewButton.append('Call to Action');
                            previewBox.append(previewButton);
                            previewBox.style.backgroundColor = color;
                            previewBox.style.padding = '10px';

                            newButtons.push(previewBox);
                        }
                    )
                ;

                previewArea.replaceChildren(...newButtons);
                // previewArea.style.padding = '30px';
                previewArea.style.display = 'grid';
                previewArea.style.gridAutoFlow = 'column';
                previewArea.style.placeContent = 'space-between';
            }
        ;

        const vars = document.createElement('link');
        vars.rel = 'stylesheet';
        vars.href = '/wp-content/themes/iana-theme/vendi-theme-parts/components/core/css/010-vars.css';
        document.head.appendChild(vars);

        const buttons = document.createElement('link');
        buttons.rel = 'stylesheet';
        buttons.href = '/wp-content/themes/iana-theme/vendi-theme-parts/components/buttons/button/button.css' + '?v=' + Math.random();
        document.head.appendChild(buttons);

        document
            .addEventListener(
                'change',
                (evt) => {
                    console.log(evt);
                    if (evt.target.tagName === 'INPUT' && evt.target.type === 'radio') {
                        // See if we're in a "buttons" area
                        const buttonArea = evt.target.closest('[data-layout=button]');
                        if (buttonArea) {
                            // Double-check that we have a known child settings area
                            const buttonSettings = buttonArea.querySelector('[data-key=field_6781a2856a8b3]');
                            if (buttonSettings) {
                                processButtonAreaClick(buttonArea);
                            } else {
                                console.error('Could not find settings area for buttons');
                            }
                        } else {
                            console.error('Could not find buttons area');
                        }
                    }
                }
            )
        ;


    }
)(window);
