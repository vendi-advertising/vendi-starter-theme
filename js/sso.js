/* global window */

(function (w) {

        'use strict'; //Force strict mode

        async function lookupEmail(input, form) {
            const data = new URLSearchParams();
            data.append('email', input.value);
            data.append('sso-target', form.getAttribute('data-sso-target'));

            const response = await fetch(
                form.getAttribute('action'),
                {
                    method: 'POST',
                    body: data,
                }
            );

            if (response.ok) {
                return await response.json();
            }

            return JSON.parse(await response.text());
        }

        const

            document = w.document,

            providers = [
                {
                    name: 'azure',
                    image: w.VENDI_SSO.images.azure,
                    text: 'Sign in with Microsoft',
                },
                {
                    name: 'github',
                    image: w.VENDI_SSO.images.github,
                    text: 'Sign in with GitHub',
                },
            ],

            createEmailForm = () => {
                const
                    form = document.createElement('form'),
                    row = document.createElement('div'),
                    label = document.createElement('label'),
                    span = document.createElement('span'),
                    input = document.createElement('input'),
                    row2 = document.createElement('div'),
                    submitDiv = document.createElement('div'),
                    submit = document.createElement('input'),
                    row3 = document.createElement('div'),
                    errorArea = document.createElement('div')
                ;

                submit.classList.add('button', 'button-primary', 'button-large');
                submit.value = 'Log In';
                submit.setAttribute('type', 'submit');

                row.classList.add('row');
                span.append(w.VENDI_SSO.strings.email);
                input.setAttribute('type', 'email');
                input.setAttribute('required', 'true');
                label.append(span, input);
                row.append(label);

                row2.classList.add('row');
                submitDiv.append(submit);
                row2.append(submitDiv);

                errorArea.classList.add('error-area');
                row3.classList.add('row');
                row3.append(errorArea);

                form.append(row, row2, row3);

                form
                    .addEventListener(
                        'submit',
                        (evt) => {
                            evt.preventDefault();

                            lookupEmail(input, form)
                                .then(
                                    (data) => {
                                        if (data.error) {
                                            errorArea.innerHTML = '';
                                            errorArea.append(data.error);
                                        } else {
                                            window.location.href = data.authorizationUrl;
                                        }
                                    }
                                )
                            ;
                        }
                    )
                ;

                return form;
            },

            createSsoButton = (svg, className, signInText) => {
                const
                    div = document.createElement('div'),
                    span = document.createElement('span'),
                    imgDiv = document.createElement('div')
                ;
                span.append(signInText);
                span.classList.add('sso-header');
                imgDiv.classList.add('sso-button');
                imgDiv.innerHTML = svg;
                div.classList.add('sso-header-and-button', className);

                div.append(imgDiv, span);

                return div;
            },

            createBackToPasswordLink = () => {
                const
                    p = document.createElement('p'),
                    span = document.createElement('span')
                ;

                span.append('← Back to password login');
                p.append(span);
                p.classList.add('back-to-password-button');

                return p;
            },

            validate = () => {
                if (!w.hasOwnProperty('VENDI_SSO')) {
                    throw 'Cannot load SSO buttons, missing global SSO variables';
                }

                Object.freeze(w.VENDI_SSO);
            },

            run = () => {

                validate();

                const
                    entireLoginArea = document.getElementById('login'),
                    existingUsernameAndPasswordArea = document.getElementById('loginform'),
                    ssoContainer = document.createElement('div'),
                    backToPassWordLink = createBackToPasswordLink(),
                    emailLookupForm = createEmailForm(),
                    buttonWrapper = document.createElement('div')
                ;

                let foundOneSystem = false;

                ssoContainer.classList.add('sso-login-selector');
                buttonWrapper.classList.add('sso-provider-wrapper');

                providers
                    .forEach(
                        (provider) => {
                            if (w.VENDI_SSO.images[provider.name]) {
                                foundOneSystem = true;
                                const button = createSsoButton(provider.image, provider.name, provider.text);
                                button
                                    .addEventListener(
                                        'click',
                                        () => {
                                            entireLoginArea.classList.add('show-sso', provider.name);
                                            emailLookupForm.setAttribute('action', w.VENDI_SSO.lookupUrl);
                                            emailLookupForm.setAttribute('data-sso-target', provider.name);
                                        }
                                    )
                                ;
                                buttonWrapper.append(button);
                            }
                        }
                    )
                ;

                if (!foundOneSystem) {
                    return;
                }

                ssoContainer.append(buttonWrapper);

                backToPassWordLink
                    .querySelector('span')
                    .addEventListener(
                        'click',
                        () => {
                            entireLoginArea.classList.remove('show-sso');
                            providers
                                .forEach(
                                    (provider) => {
                                        entireLoginArea.classList.remove(provider.name);
                                    }
                                )
                            ;
                        }
                    )
                ;

                ssoContainer.append(emailLookupForm);
                existingUsernameAndPasswordArea.after(ssoContainer);
                ssoContainer.after(backToPassWordLink);
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
