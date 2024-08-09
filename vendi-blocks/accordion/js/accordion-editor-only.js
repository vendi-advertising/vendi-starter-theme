/*global window */

initializeBlock = function (block) {
    block
        .each(
            (i, e) => {
                e
                    .addEventListener(
                        'click',
                        (e) => {
                            if (e.target.tagName === 'DIV') {
                                // e.preventDefault();
                            }
                        }
                    )
                ;

            }
        )
    ;
};

document.addEventListener('DOMContentLoaded', () => {
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=vendi/accordion', initializeBlock);
    } else {
        // initializeBlock();
    }
});
