/* global window */
(function (w) {

    const
        document = w.document,
        viewedAlerts = [],
        cookieName = 'dismissedAlerts',
        daysToStoreDismissedAlertsInCookie = 100,
        // Only change this is there's a reason to invalidate __every__ alert in the entire system
        alertSystemVersion = 1,
        cookie = getCookie(cookieName, '&'),
        previouslyClosedCookieData = cookie.split("&"),
        currentDate = new Date(Date.now()),
        cssClassToRemoveToShortAlert = "hidden",
        cssClassToAddToHidePreviouslyShownAlerts = "hide"
    ;

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name, defaultValue) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === " ") {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) === 0) {
                return c.substring(nameEQ.length, c.length);
            }
        }

        return defaultValue;
    }

    document.querySelectorAll("[data-role~=alert]").forEach((singleAlert) => {
        const
            alertEndDate = new Date(singleAlert.getAttribute("data-end-date")),
            alertStartDate = new Date(singleAlert.getAttribute("data-start-date")),
            alertID = singleAlert.getAttribute("data-id"),
            alertVersion = singleAlert.getAttribute('data-version'),
            currentAlertData = ['v' + alertSystemVersion, alertID, alertVersion].join('~')
        ;

        if (previouslyClosedCookieData.includes(currentAlertData)) {
            return;
        }

        //Only show current or future alerts
        if ((alertEndDate.getTime() >= currentDate.getTime()) && (alertStartDate.getTime() <= currentDate.getTime())) {
            singleAlert.classList.remove(cssClassToRemoveToShortAlert);
        }

        //close alert button
        singleAlert.querySelectorAll("[data-role~=dismiss-alert]").forEach((closeButton) => {
            closeButton.addEventListener("click", () => {

                singleAlert.classList.add(cssClassToAddToHidePreviouslyShownAlerts);

                //Avoid duplicates
                if ((!previouslyClosedCookieData.includes(currentAlertData))) {
                    viewedAlerts.push(currentAlertData);
                }
                
                if (viewedAlerts.length > 0) {

                    let cookieValue = getCookie(cookieName);
                    if (cookieValue === null) {
                        cookieValue = '&';
                    }

                    let newCookieValue = cookieValue + '&' + viewedAlerts.join("&");
                    setCookie(cookieName, newCookieValue, daysToStoreDismissedAlertsInCookie);
                }
            });
        });
    });

})(window);
