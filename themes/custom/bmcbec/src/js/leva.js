if($(".image_carousel .item").length > 0) {
    $(".image_carousel .slides").slick({
        arrows: true,
        prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left"></i>',
        nextArrow: '<div class="slick-next"><i class="fa fa-angle-right"></i>',
        dots: false,
        infinite: true,
        speed: 200,
        easing: 'linear',
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    arrows: true
                }
            }
        ]
    });
}