jQuery(document).ready(() => {
    jQuery('[data-role~=vendi-carousel-slides]').slick({
        ltr: true,
        dots: true,
        arrows: true,
        autoplay: false,
        nextArrow: window.VENDI_CAROUSEL_ARROW_RIGHT,
        prevArrow: window.VENDI_CAROUSEL_ARROW_LEFT,
    })
})
