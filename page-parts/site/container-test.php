<style>
    .accordion {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        --accordion-icon-width: 1.8rem;

        details {
            border-bottom: 1px solid var(--color-starter-theme-light-black);

            & > * {
                display: grid;
                grid-template-columns: var(--accordion-icon-width) 1fr;
                column-gap: 1rem;
            }

            /* This ensures that the copy of the accordion aligns with the headline in the summary */
            & > :not(summary) > * {
                grid-column: 2;
            }

            summary {
                list-style: none;
                font-weight: var(--font-weight-h1);
                padding-block: 2rem;
                user-select: none;
                cursor: pointer;

                .expand-collapse-single-item svg {
                    width: var(--accordion-icon-width);
                }

                .expand-collapse-single-item svg path {
                    stroke: var(--color-starter-theme-primary);
                    transition: all 0.25s ease;
                }

                details[open] .expand-collapse-single-item svg {
                    transition: all 0.25s ease;
                }

                details[open] .expand-collapse-single-item svg path {
                    stroke: var(--color-starter-theme-secondary);
                }

                details[open] .expand-collapse-single-item svg .h-line {
                    opacity: 0;
                }

                details[open] .expand-collapse-single-item svg {
                    rotate: 90deg;
                    rotation-point: center;
                }
            }
        }
    }
</style>
<section class="grid grid-col-4 gap-large">
    <div class="accordion">
        <details class="single-accordion-item">
            <summary>
                <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
                <span>Accordion 1</span>
            </summary>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
        </details>
        <details class="single-accordion-item">
            <summary>
                <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
                <span>Accordion 2</span>
            </summary>
            <div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
        </details>
    </div>

    <div class="basic-copy grid-col-span-3">
        <h2>Lorem ipsum dolor sit amet</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <h3>Lorem ipsum dolor sit amet</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
</section>

<br/>
<br/>

<div class="accordion">
    <details class="single-accordion-item">
        <summary>
            <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
            <span>Accordion 1</span>
        </summary>
        <div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </details>
    <details class="single-accordion-item">
        <summary>
            <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
            <span>Accordion 2</span>
        </summary>
        <div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </details>
</div>
