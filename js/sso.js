/* global window */

(function (w) {

        'use strict'; //Force strict mode

        const

            document = w.document,

            popupWindow = (url, title, width, height) => {
                const
                    left = (w.screen.width / 2) - (width / 2),
                    top = (w.screen.height / 2) - (height / 2),
                    s = `toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=ues,copyhistory=no,width=${width},height=${height},top=${top},left=${left}`
                ;
                const nw = window.open(url, title, s);
                nw.moveTo(left, top);
                return nw;
            },

            createAzureButton = () => {
                if (!w.hasOwnProperty('VENDI_SSO')) {
                    throw 'Cannot load SSO buttons, missing global SSO variables';
                }

                const img = document.createElement('img');
                img.src = w.VENDI_SSO.images.azure;

                img
                    .addEventListener(
                        'click',
                        () => {
                            const nw = popupWindow('', 'Sign in with Microsoft', 480, 600);
                            nw.document.body.innerHTML = 'test';
                        }
                    )
                ;

                return img;
            },

            run = () => {


                const existingForm = document.getElementById('loginform');
                const newForm = document.createElement('form');
                newForm.classList.add('sso-login-selector');

                const azureButton = createAzureButton();

                newForm.append(azureButton);
                existingForm.after(newForm);
            },

            init = () => {

                if (['complete', 'interactive'].includes(document.readyState)) {
                    run();
                } else {
                    document.addEventListener('DOMContentLoaded', run);
                }
            }

        ;

        //Kick everything off
        init();
    }
)
(window);
