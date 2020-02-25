jQuery( function ( $ ) { 
	$(".slider-download-schmal .slick-slider").slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		speed: 1000,
		infinite: true,
		focusOnSelect: true,
		prevArrow: '<button type="button" class="slick-prev"></button>',
		nextArrow: '<button type="button" class="slick-next"></button>',
	});
});
