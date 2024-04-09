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

            createGitHubButton = () => {
                const
                    div = document.createElement('div'),
                    h2 = document.createElement('h2'),
                    img = document.createElement('img')
                ;
                h2.append('Sign in with GitHub');
                img.src = w.VENDI_SSO.images.github;
                h2.classList.add('sso-header');
                img.classList.add('sso-button');
                div.classList.add('sso-header-and-button', 'github');

                div.append(h2, img);

                return div;
            },

            createAzureButton = () => {

                const
                    div = document.createElement('div'),
                    h2 = document.createElement('h2'),
                    img = document.createElement('img')
                ;
                h2.append('Sign in with Microsoft');
                img.src = w.VENDI_SSO.images.azure;
                h2.classList.add('sso-header');
                img.classList.add('sso-button');
                div.classList.add('sso-header-and-button', 'azure');

                div.append(h2, img);

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
                    emailLookupForm = createEmailForm()
                ;

                let foundOneSystem = false;

                ssoContainer.classList.add('sso-login-selector');

                if (w.VENDI_SSO.images.azure) {
                    foundOneSystem = true;
                    const azureButton = createAzureButton();
                    azureButton
                        .querySelector('img')
                        .addEventListener(
                            'click',
                            () => {
                                entireLoginArea.classList.add('show-sso', 'azure');
                                emailLookupForm.setAttribute('action', w.VENDI_SSO.lookupUrl);
                                emailLookupForm.setAttribute('data-sso-target', 'azure');
                            }
                        )
                    ;
                    ssoContainer.append(azureButton);
                }

                if (w.VENDI_SSO.images.github) {
                    foundOneSystem = true;
                    const gitHubButton = createGitHubButton();
                    gitHubButton
                        .querySelector('img')
                        .addEventListener(
                            'click',
                            () => {
                                entireLoginArea.classList.add('show-sso', 'github');
                                emailLookupForm.setAttribute('action', w.VENDI_SSO.lookupUrl);
                                emailLookupForm.setAttribute('data-sso-target', 'github');
                            }
                        )
                    ;
                    ssoContainer.append(gitHubButton);

                }

                if (!foundOneSystem) {
                    return;
                }

                backToPassWordLink
                    .querySelector('span')
                    .addEventListener(
                        'click',
                        () => {
                            entireLoginArea.classList.remove('show-sso', 'azure', 'github');
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
