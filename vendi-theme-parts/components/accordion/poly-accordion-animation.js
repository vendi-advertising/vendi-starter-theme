/* global window */
(function (window) {
    'use strict';
    const document = window.document;
    const run = () => {
            class Accordion {
                constructor(el, container) {
                    // Store the <details> element
                    this.el = el;

                    this.container = container;

                    // Store the <summary> element
                    this.summary = el.querySelector("summary");

                    this.link = el.querySelector("summary a");

                    this.svg = el.querySelector("summary svg:last-child");
                    // Store the <div class="content"> element
                    this.content = el.querySelector(".content-area");

//             // Store the animation object (so we can cancel it if needed)
                    this.animation = null;
//             // Store if the element is closing
                    this.isClosing = false;
//             // Store if the element is expanding
                    this.isExpanding = false;

//             // Detect user clicks on the summary element
                    this.summary.addEventListener("click", (e) => this.onClick(e));

                    this.el.addEventListener("close", () => this.shrink());
                }

                onClick(e) {
                    if (e.target.tagName.toLowerCase() !== 'a') {
                        e.preventDefault();
                    }
                    // Stop default behaviour from the browser
                    // Add an overflow on the <details> to avoid content overflowing
                    this.el.style.overflow = "hidden";
                    // Check if the element is being closed or is already closed
                    if (this.isClosing || !this.el.open) {
                        this.open();
                        // Check if the element is being opened or is already open
                    } else if (this.isExpanding || this.el.open) {
                        this.shrink();
                    }
                }

                getTransitionDuration(openOrClose) {
                    if ('open' === openOrClose && this.el.hasAttribute('data-transition-duration-open-in-ms')) {
                        return parseInt(this.el.getAttribute('data-transition-duration-open-in-ms'), 10);
                    }

                    if ('close' === openOrClose && this.el.hasAttribute('data-transition-duration-close-in-ms')) {
                        return parseInt(this.el.getAttribute('data-transition-duration-close-in-ms'), 10);
                    }

                    if (this.el.hasAttribute('data-transition-duration-in-ms')) {
                        return parseInt(this.el.getAttribute('data-transition-duration-in-ms'), 10);
                    }

                    return 200;
                }

                shrink() {

                    // Set the element as "being closed"
                    this.isClosing = true;


                    // Store the current height of the element
                    const startHeight = `${this.el.offsetHeight}px`;
                    // Calculate the height of the summary
                    const endHeight = `${this.summary.offsetHeight}px`;
                    // If there is already an animation running
                    if (this.animation) {
                        // Cancel the current animation
                        this.animation.cancel();
                    }

                    // Start a WAAPI animation
                    this.animation = this.el.animate(
                        {
                            // Set the keyframes from the startHeight to endHeight
                            height: [startHeight, endHeight],
                        },
                        {
                            duration: this.getTransitionDuration('close'),
                            easing: "linear",
                        }
                    )
                    ;

                    // When the animation is complete, call onAnimationFinish()
                    this.animation.onfinish = () => this.onAnimationFinish(false);
                    // If the animation is cancelled, isClosing variable is set to false
                    this.animation.oncancel = () => (this.isClosing = false);

                    this.el.classList.add("closing");
                    this.el.classList.toggle('accordion-open');

                }

                open() {
                    // Apply a fixed height on the element
                    this.el.style.height = `auto`;
                    // Force the [open] attribute on the details element
                    this.el.open = true;

                    if (this.container) {
                        this.closeAllButThis();
                    }
                    // Wait for the next frame to call the expand function
                    window.requestAnimationFrame(() => this.expand());
                }

                closeAllButThis() {
                    // Triggering "close" event for any attached listeners on the <dialog>.
                    const closeEvent = new window.CustomEvent("close", {
                        bubbles: false, cancelable: false,
                    });

                    this.container.querySelectorAll("details").forEach((item) => {
                        if (item !== this.summary) {
                            item.dispatchEvent(closeEvent);
                        }
                    });
                }

                expand() {
                    // Set the element as "being expanding"
                    this.isExpanding = true;
                    // Get the current fixed height of the element
                    const startHeight = `${this.summary.offsetHeight}px`;
                    // Calculate the open height of the element (summary height + content height)
                    const endHeight = `${this.el.offsetHeight}px`;

                    this.el.classList.add('opening');
                    this.el.classList.toggle('accordion-open');


                    // If there is already an animation running
                    if (this.animation) {
                        // Cancel the current animation
                        this.animation.cancel();
                    }

                    // Start a WAAPI animation
                    this.animation = this.el.animate({
                        // Set the keyframes from the startHeight to endHeight
                        height: [startHeight, endHeight],
                    }, {
                        duration: this.getTransitionDuration('open'), easing: "linear",
                    });

                    // When the animation is complete, call onAnimationFinish()
                    this.animation.onfinish = () => this.onAnimationFinish(true);

                    // If the animation is cancelled, isExpanding variable is set to false
                    this.animation.oncancel = () => {
                        this.onAnimationCancel();

                    };
                }

                onAnimationCancel() {
                    this.isExpanding = false;
                    this.el.classList.remove("opening");
                    this.el.classList.remove("closing");

                }

                onAnimationFinish(open) {
                    // Set the open attribute based on the parameter
                    this.el.open = open;
                    // Clear the stored animation
                    this.animation = null;
                    // Reset isClosing & isExpanding
                    this.isClosing = false;
                    this.isExpanding = false;
                    // Remove the overflow hidden and the fixed height
                    this.el.style.height = this.el.style.overflow = "";

                    this.el.classList.remove("opening");
                    this.el.classList.remove("closing");
                }
            }

            document.querySelectorAll("details").forEach((el) => {
                if (!el.hasAttribute("data-always-open")) {
                    new Accordion(el, null);
                }
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

})(window);

