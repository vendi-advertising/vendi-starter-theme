/* global window */
(function (w) {
    'use strict';

    const
        document = w.document,
        routeBase = '/__vendi-front-end-edit/',
        qsParamPostId = 'vendi-post-id',
        qsParamComponentId = 'vendi-component-id',
        qsParamAction = 'vendi-action',
        postId = w.VENDI_FRONT_END_EDIT.postId,

        generateRandomString = () => {
            return window.crypto.randomUUID();
        },

        getGeneralComponentUrl = (postId, componentId, action) => {
            const url = new w.URL(routeBase, w.location.origin);
            url.searchParams.append(qsParamPostId, postId);
            url.searchParams.append(qsParamComponentId, componentId);
            url.searchParams.append(qsParamAction, action);
            return url;
        },

        getRefreshComponentUrl = (postId, componentId, requestId) => {
            const url = getGeneralComponentUrl(postId, componentId, 'render');
            url.searchParams.append('vendi-request-id', requestId);
            return url;
        },

        getEditComponentUrl = (postId, componentId) => {
            return getGeneralComponentUrl(postId, componentId, 'edit');
        },

        refreshComponent = (component, requestId) => {
            const
                idx = component.dataset.editableComponentIndex,
                url = getRefreshComponentUrl(postId, idx, requestId)
            ;

            component.classList.add('front-end-edit-loading');

            // Fetch with AJAX
            const xhr = new w.XMLHttpRequest();
            xhr.open('GET', url.toString());
            xhr.onload = () => {
                if (xhr.status === 200) {
                    // The response text is a JSON object with a "content" key
                    const response = JSON.parse(xhr.responseText);

                    const wrapper = component.querySelector('& > .component-wrapper');
                    if (wrapper) {
                        const
                            componentNames = wrapper.getAttribute('data-component-name'),
                            componentIndex = wrapper.getAttribute('data-component-index'),
                            matchingStyleTag = document.querySelector(`style[data-component-name="${componentNames}"][data-component-index="${componentIndex}"]`)
                        ;

                        if (matchingStyleTag) {
                            matchingStyleTag.remove();
                        }
                    }

                    // We need to replace the entire component with this new content
                    component.outerHTML = response.content;

                    lookForEditableAreas();
                } else {
                    w.console.error('Request failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.send();
        },

        handleEditButtonClick = (evt) => {
            const
                editModalAttribute = 'data-modal-for-edit',
                button = evt.target,
                parent = button.parentElement,
                idx = parent.dataset.editableComponentIndex,
                modal = document.createElement('dialog'),
                iframe = document.createElement('iframe'),
                footerSaveButton = document.createElement('button'),
                footerSaveAndCloseButton = document.createElement('button'),
                closeButton = document.createElement('button'),
                modalHeader = document.createElement('header'),
                modalMain = document.createElement('main'),
                modalFooter = document.createElement('footer')
            ;

            modal.setAttribute(editModalAttribute, '');
            parent.append(modal);

            closeButton
                .addEventListener(
                    'click',
                    () => {
                        modal.close();
                    }
                )
            ;
            closeButton.classList.add('front-end-edit-close-button');

            footerSaveButton.textContent = 'Save';
            footerSaveButton
                .addEventListener(
                    'click',
                    () => {
                        iframe.contentWindow.postMessage('save', '*');
                    }
                )
            ;

            footerSaveAndCloseButton.textContent = 'Save and Close';
            footerSaveAndCloseButton
                .addEventListener(
                    'click',
                    () => {
                        iframe.contentWindow.postMessage('save-and-close', '*');
                    }
                )
            ;

            modalHeader.append('Edit Component');
            closeButton.setAttribute('aria-label', 'Close modal');
            modalFooter.append(footerSaveAndCloseButton, footerSaveButton);
            modalHeader.append(closeButton);
            modalMain.append(iframe);
            iframe.classList.add('front-end-edit-iframe');
            modal.append(modalHeader, modalMain, modalFooter);

            // The iframe posts a message after successfully saving the component, listen for it.
            let isDirty = false;
            w.onmessage = (e) => {
                if (e.data === 'updated') {
                    isDirty = true;
                }

                if (e.data === 'close-modal') {
                    modal.close();
                }
            };

            modal.addEventListener(
                'close',
                () => {
                    if (isDirty) {
                        refreshComponent(parent, generateRandomString());
                    }

                    // When the modal closes, destroy it, mostly to guarantee that the iframe isn't still running, including
                    // and forward/backward history
                    iframe.src = '';
                    modal.remove();
                }
            );

            iframe.src = getEditComponentUrl(postId, idx).toString();

            modal.showModal();
        },

        createEditaButton = () => {
            const button = document.createElement('button');
            button.textContent = 'Edit';
            button.classList.add('front-end-edit-button');
            button.addEventListener('click', handleEditButtonClick);
            return button;
        },

        lookForEditableAreas = () => {
            let idx = 0;
            document
                .querySelectorAll('main > .content-components > section')
                .forEach(
                    (editableArea) => {
                        if (!editableArea.classList.contains('front-end-editable-area')) {
                            editableArea.classList.add('front-end-editable-area');
                            editableArea.appendChild(createEditaButton());
                            editableArea.dataset.editableComponentIndex = idx.toString(10);
                        }

                        idx++;
                    }
                )
            ;
        },

        ensureGlobalState = () => {
            if (!w.VENDI_FRONT_END_EDIT || !w.VENDI_FRONT_END_EDIT.postId) {
                w.alert('VENDI_FRONT_END_EDIT.postId is not set');
                throw new Error('VENDI_FRONT_END_EDIT.postId is not set');
            }
        },

        run = () => {
            ensureGlobalState();
            lookForEditableAreas();
        },

        init = () => {
            if (['complete', 'interactive'].includes(document.readyState)) {
                run();
            } else {
                document.addEventListener('DOMContentLoaded', run);
            }
        };
    init();
})(window);
