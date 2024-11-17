/* global window */
(function (w) {
    'use strict'

    const
        document = w.document,
        console = w.console,

        run = () => {
            const mobileNavContainer = document.querySelector('[data-role="mobileNavContainer"]');
            if (!mobileNavContainer || !window.mainNav || !window.topNav) {
                console.warn('Menu container/items not found');
                return;
            }

            const findChildren = (parentItem, navItem) => {
                if (parseInt(navItem.menu_item_parent) === parentItem.ID) {
                    if (!parentItem.children) {
                        parentItem.children = [];
                    }
                    parentItem.children.push(navItem);
                }
            }
            const organizeMenuItems = (menuItems) => {
                let navItems = {};
                navItems.l2 = [];
                menuItems.forEach((item) => {
                    if (parseInt(item.menu_item_parent) === 0) {
                        navItems.l2.push(item);
                    }
                });

                navItems.l2.forEach((l2Item) => {

                    menuItems.forEach((item) => {
                        findChildren(l2Item, item);
                    });

                    if (!l2Item.children) {
                        return;
                    }

                    l2Item.children.forEach((child) => {
                        menuItems.forEach((item) => {
                            findChildren(child, item);
                        });

                        if (child.children) {
                            child.children.forEach((child2) => {
                                menuItems.forEach((item) => {
                                    findChildren(child2, item);
                                });
                            });
                        }

                    });
                });

                return navItems;
            }

            const mainNavItems = organizeMenuItems(window.mainNav);
            const topNavItems = organizeMenuItems(window.topNav);
            const buildMobileNav = () => {
                //create dialog
                let dialog = document.createElement('dialog');
                dialog.classList.add('mobile-nav');
                dialog.setAttribute('data-role', 'mobileNav')
                dialog.open = false;

                //remove active classes function
                const removeActiveClasses = (breakAfterFirstIteration = true) => {
                    //MUST be in descending order because loop will break after its first successful iteration
                    const activeMenuItemClasses = ['.l4-menu-item.active', '.l3-menu-item.active', '.l2-container.active'];

                    for (let i = 0; i < activeMenuItemClasses.length; i++) {
                        let activeClass = activeMenuItemClasses[i];
                        if (dialog.querySelectorAll(activeClass) && dialog.querySelectorAll(activeClass).length > 0) {
                            dialog.querySelectorAll(activeClass).forEach((activeItem) => {
                                activeItem.classList.remove('active');
                            });
                            if (breakAfterFirstIteration){
                                break;
                            }
                        }
                    }
                };

                //create close button
                let closeBtn = document.createElement('button');
                closeBtn.classList.add('close-button');
                closeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="17.828" height="17.828"><path d="m2.828 17.828 6.086-6.086L15 17.828 17.828 15l-6.086-6.086 6.086-6.086L15 0 8.914 6.086 2.828 0 0 2.828l6.085 6.086L0 15l2.828 2.828z"/></svg>`;
                closeBtn.addEventListener('click', () => {
                    dialog.close();
                    removeActiveClasses(false);
                });

                //append close button
                dialog.appendChild(closeBtn);

                //create back button
                const backButton = document.createElement('button');
                backButton.classList.add('back-button');
                backButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M32 15H3.41l8.29-8.29-1.41-1.42-10 10a1 1 0 0 0 0 1.41l10 10 1.41-1.41L3.41 17H32z" data-name="4-Arrow Left"/></svg>`;
                backButton.addEventListener('click', () => {
                    removeActiveClasses();
                });

                //append back button
                dialog.appendChild(backButton);

                //expand button function
                const createExpandButton = (container, className) => {
                    const expandButton = document.createElement('button');
                    expandButton.classList.add(className);
                    expandButton.setAttribute('data-role', 'expandButton');
                    expandButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><path style="fill:#232326" d="m17.5 5.999-.707.707 5.293 5.293H1v1h21.086l-5.294 5.295.707.707L24 12.499l-6.5-6.5z" data-name="Right"/></svg>`;
                    expandButton.addEventListener('click', () => {
                        container.classList.add('active');
                    });
                    container.appendChild(expandButton);
                }

                const createLink = (item, classNames, topLevelBool, parentDiv = null, level, childrenDiv = null) => {
                    const container = document.createElement('div');
                    const link = document.createElement('a');
                    classNames.forEach((className) => {
                        container.classList.add(className);
                    })
                    link.classList.add('expand-link');
                    link.href = item.url;
                    link.innerHTML = item.title;
                    container.appendChild(link);

                    //Run the function again one level deeper if there are children
                    if (item.children) {
                        level = parseInt(level);
                        level++;
                        createExpandButton(container, 'expand-arrow')
                        const childrenContainer = document.createElement('div');
                        childrenContainer.classList.add('mobile-menu-children', 'l' + level + '-container');
                        item.children.forEach((child) => {
                            createLink(child, ['l' + level + '-menu-item', 'mobile-menu-item'], false, container, level, childrenContainer);
                        })
                    }
                    if (topLevelBool) {
                        dialog.appendChild(container);
                    } else if (childrenDiv) {
                        childrenDiv.appendChild(container);
                        parentDiv.appendChild(childrenDiv);
                    } else {
                        //TODO: add error handling
                        console.warn('something went wrong');
                    }

                }

                //Main Nav
                mainNavItems.l2.forEach((l2) => {
                    (createLink(l2, ['l2-container', 'mobile-menu-item'], true, null, 2, null));
                })


                //Top Nav
                mobileNavContainer.appendChild(dialog);

                const mobileTopNavContainer = document.createElement('div');
                mobileTopNavContainer.classList.add('mobile-top-nav-container');
                topNavItems.l2.forEach((topNavL2) => {
                    const topNavl2Container = document.createElement('div');
                    const topNavl2Link = document.createElement('a');
                    topNavl2Container.classList.add('l2-container', 'mobile-menu-item', 'top-nav-menu-item');
                    topNavl2Link.classList.add('l2-link');
                    topNavl2Link.href = topNavL2.url;
                    topNavl2Link.innerHTML = topNavL2.title;
                    topNavl2Container.appendChild(topNavl2Link);
                    mobileTopNavContainer.appendChild(topNavl2Container);
                });

                dialog.appendChild(mobileTopNavContainer);

            }

            //Start:open mobile nav button
            const mobileNavButton = document.querySelector('[data-role="mobileNavButton"]');

            if (!mobileNavButton) {
                console.warn('Mobile Nav Button not found');
                return;
            }

            // build the nav upon click
            mobileNavButton.addEventListener('click', () => {
                buildMobileNav();
                const dialog = document.querySelector('[data-role="mobileNav"]');
                if (!dialog) {
                    console.warn('Mobile Nav Dialog not found');
                    return;
                }
                dialog.showModal();
            });


        },

        init = () => {
            if (['complete', 'interactive'].includes(document.readyState)) {
                run();
            } else {
                document.addEventListener('DOMContentLoaded', run);
            }
        };
    init();
})(window)
