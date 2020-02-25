jQuery( function ( $ ) {
    var sliderDownload = $(".slider-download");

    var maxSlidesToShow = 3;
    var childCount = sliderDownload.children().length;
    var options = {
        centerPadding: '0px',
        slidesToScroll: 1,
        autoplay: true,
        speed: 1000,
        infinite: childCount > 3,
        focusOnSelect: true,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        responsive: [{
            breakpoint: 768,
            settings: {
                infinite: true,
                slidesToShow: Math.min(2, childCount),
                centerMode: 2 <= childCount,
                centerPadding: '0px',
            }
        },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    centerMode: 1 <= childCount,
                    infinite: childCount > 1,
                    centerPadding: '0px',
                }
            }]
    };

    options.slidesToShow = Math.min(childCount, maxSlidesToShow);
    options.centerMode = maxSlidesToShow < childCount;

    if (options.centerMode) {
        sliderDownload.addClass('slick-center-mode');
    } else {
        sliderDownload.addClass('slick-no-center-mode');
    }

    if (childCount < maxSlidesToShow) {
        sliderDownload.removeClass('col-10');
        sliderDownload.addClass('col-md-10 col-lg-' + childCount * 3);
    }

    sliderDownload.slick(options);

});