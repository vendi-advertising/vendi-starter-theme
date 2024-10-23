/* global window */
(function (window) {
    'use strict';

    const COOKIE_NAME = 'WEBSITE-ACCEPT-COOKIES';

    const
        document = window.document,
        console = window.console,
        setCookie = (name, value, days) => {
            const d = new Date;
            d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
            document.cookie = name + '=' + value + ';path=/;expires=' + d.toGMTString();
        },
        getCookie = (name) => {
            const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
            return v ? v[2] : null;
        },
        cookieBanner = document.querySelector('[data-role~=cookieBanner]'),
        acceptCookie = document.querySelector('[data-role~=acceptCookie]')
    ;

    if (!cookieBanner) {
        return;
    }

    if (!acceptCookie) {
        console.debug('No accept cookie button found. Exiting.');
        return;
    }

    acceptCookie
        .addEventListener(
            'click',
            () => {
                setCookie(COOKIE_NAME, 'true', {expires: 365});
                cookieBanner.classList.add('hidden');
            }
        )
    ;

    if (!getCookie(COOKIE_NAME)) {
        cookieBanner.classList.remove('hidden');
    }

})(window);
