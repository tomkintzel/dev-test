jQuery(".slider-brand").slick({
	slidesToShow: 5,
	slidesToScroll: 1,
	autoplay: true,
	infinite: true,
	speed: 1000,
	focusOnSelect: true,
	prevArrow: '<button type="button" class="slick-prev"></button>',
	nextArrow: '<button type="button" class="slick-next"></button>',
	responsive: [{
	      breakpoint: 992,
	      settings: {
	      	slidesToShow: 3,
			centerMode: true,
			centerPadding: '0px',
	      }
	},
	{
	      breakpoint: 768,
	      settings: {
	      	slidesToShow: 2,
			centerMode: true,
			centerPadding: '0px',
	      }
	},
	{
	      breakpoint: 576,
	      settings: {
	      	slidesToShow: 1,
			centerMode: true,
			centerPadding: '0px',
	      }
	}]
});