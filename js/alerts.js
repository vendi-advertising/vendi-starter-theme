/* jshint esversion: 6, esversion: 6 */
/* global window */
(function (window) {

    const

        // Variable aliasing
        document = window.document,
        console = window.console,

        createSingleAlert = (alert) => {

            const singleAlert = document.createElement('section');
            singleAlert.classList.add('alert');
            singleAlert.classList.add(alert.alert_type);
            singleAlert.classList.add(alert.alert_style);
            singleAlert.classList.add(alert.display_mode);
            singleAlert.setAttribute('data-alert-id', alert.id);
            singleAlert.setAttribute('data-alert-version', alert.alert_version);
            singleAlert.setAttribute('data-alert-start-date', alert.start_date);
            singleAlert.setAttribute('data-alert-end-date', alert.end_date);
            singleAlert.setAttribute('data-alert-background-color', alert.background_color.label);
            singleAlert.setAttribute('data-alert-priority', alert.priority);

            if (alert.background_color) {
                singleAlert.style.backgroundColor = alert.background_color.color;
            }
            if (alert.headline) {
                const headline = document.createElement('p');
                headline.classList.add('headline');
                headline.textContent = alert.headline;
                singleAlert.appendChild(headline);
            }
            if (alert.primary_message) {
                const primaryMessage = document.createElement('div');
                primaryMessage.classList.add('primary-message');
                primaryMessage.innerHTML = alert.primary_message;
                singleAlert.appendChild(primaryMessage);
            }

            // console.log(alert);

            return singleAlert;
        },

        run = () => {
            const alertContainer = document.createElement('div');
            alertContainer.classList.add('site-alerts');

            const localAlerts = [];

            window
                .vendi_alerts
                .alerts
                .forEach(
                    (alert) => {
                        localAlerts.push(createSingleAlert(alert));
                    }
                )
            ;

            localAlerts
                .sort(
                    (a, b) => {
                        const
                            ap = parseInt(a.getAttribute('data-alert-priority')),
                            bp = parseInt(b.getAttribute('data-alert-priority'))
                        ;

                        if (ap < bp) {
                            return 1;
                        }
                        if (ap > bp) {
                            return -1;
                        }
                        return 0;
                    }
                )
            ;

            localAlerts
                .forEach(
                    (alert) => {
                        alertContainer.appendChild(alert);
                    }
                )
            ;

            document.body.insertBefore(alertContainer, document.body.firstChild);
        },

        load = () => {
            if (!window.vendi_alerts || !window.vendi_alerts.alerts) {
                console.error('vendi_alerts.alerts is not defined');
                return;
            }
            run();
        },

        init = () => {
            if (['complete', 'loaded', 'interactive'].indexOf(document.readyState) >= 0) {
                // If the DOM is already set then just load
                load();
            } else {
                //Otherwise, wait for the ready event
                document.addEventListener('DOMContentLoaded', load);
            }
        }
    ;

    // Boot
    init();
}(window));


// (function () {
//
//     let viewedAlerts = [];
//
//     const cookieName = 'dismissedAlerts';
//
//     function setCookie(name, value, days) {
//         let expires = "";
//         if (days) {
//             const date = new Date();
//             date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
//             expires = "; expires=" + date.toUTCString();
//         }
//         document.cookie = name + "=" + (value || "") + expires + "; path=/";
//     }
//
//     function getCookie(name) {
//         const nameEQ = name + "=";
//         const ca = document.cookie.split(";");
//         for (let i = 0; i < ca.length; i++) {
//             let c = ca[i];
//             while (c.charAt(0) === " ") {
//                 c = c.substring(1, c.length);
//             }
//             if (c.indexOf(nameEQ) === 0) {
//                 return c.substring(nameEQ.length, c.length);
//             }
//         }
//         return null;
//     }
//
//     function eraseCookie(name) {
//         document.cookie = name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//     }
//
//     let cookie = getCookie(cookieName);
//
//     if (cookie === null) {
//         cookie = '&';
//     }
//
//     let cookieValues = cookie.split("&");
//     let currentDate = new Date(Date.now());
//
//     document.querySelectorAll("[data-role~=alert]").forEach((singleAlert) => {
//         let alertEndDate = new Date(singleAlert.getAttribute("data-endDate"));
//         let alertStartDate = new Date(singleAlert.getAttribute("data-startDate"));
//         let alertID = singleAlert.getAttribute("data-id");
//         let alertVersion = singleAlert.getAttribute('data-version');
//         let alertData = 'v1~' + alertID + '~' + alertVersion;
//
//         //Only show current or future alerts
//         if ((alertEndDate.getTime() >= currentDate.getTime()) && (alertStartDate.getTime() <= currentDate.getTime())) {
//
//             //compares alert ID to cookie ID
//             if (!cookieValues.includes(alertData)) {
//                 singleAlert.classList.remove("hidden");
//             }
//         }
//
//         //close alert button
//         singleAlert
//             .querySelectorAll("svg").forEach((closeButton) => {
//             closeButton.addEventListener("click", () => {
//
//                 // This bit of code is for how the header is rendered. In order to maintain a fixed header
//                 // that also stil takes up space, the header is duplicated with its clone having its
//                 // visibility hidden. Because alerts lives in the topnav, there are also cloned alerts
//                 // This code finds the associated cloned alerts and hides them when the original is clicked on
//                 // so that the headers will always be the same height
//                 document.querySelectorAll('[data-role~=alert]').forEach((hideAlert) => {
//                     if (hideAlert.getAttribute('data-id') === alertID) {
//                         hideAlert.classList.add("hidden");
//                     }
//
//                 })
//
//                 //Avoid duplicates
//                 if ((!cookieValues.includes(alertData)) && (alertData !== '')) {
//                     viewedAlerts.push(alertData);
//                 }
//
//                 let cookieValue = getCookie(cookieName);
//                 if (cookieValue === null) {
//                     cookieValue = '&';
//                 }
//
//                 if (viewedAlerts.length > 0) {
//                     let newCookieValue = cookieValue + '&' + viewedAlerts.join("&");
//                     setCookie(cookieName, newCookieValue, 100);
//                 }
//             });
//         });
//     });
//     // eraseCookie('cookie');
// })();


//window.vendi_alerts.alerts












