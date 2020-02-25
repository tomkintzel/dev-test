(function($) {
	'use strict';
	if(Modernizr.touch === false) {
		var width, height;
		$(document).ready(function() {
			$('#main-wrap').addClass('dfd-one-page-scroll-layout');
			$('#footer-wrap').remove();

            if (jQuery(window).height() > 780) {
                $('#layout.one-page-scroll').slick({
                    infinite: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                    draggable: false,
                    autoplay: false,
                    speed: 700,
                    vertical: true,
                    customPaging: function(slider, i) {
                        var $tooltip = '';
                        if($(slider.$slides[i]).data('dfd-dots-title')) {
                            $tooltip = '<span><span>' + $(slider.$slides[i]).data('dfd-dots-title') + '</span></span>';
                        }
                        return '<button type="button">' + $(slider.$slides[i]).data('slick-index') + '</button>' + $tooltip;
                    }
                });
				var debounce = function(func, wait, immediate) {
					var timeout;
					return function() {
						var context = this, args = arguments;
						var later = function() {
							timeout = null;
							if (!immediate) func.apply(context, args);
						};
						var callNow = immediate && !timeout;
						clearTimeout(timeout);
						timeout = setTimeout(later, wait);
						if (callNow) func.apply(context, args);
					};
				};
                var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? 'DOMMouseScroll' : 'mousewheel';
                $(window).bind(mousewheelevt, debounce(function(e){
                    var ev = window.event || e;
                    ev = ev.originalEvent ? ev.originalEvent : ev;
                    var delta = ev.detail ? ev.detail*(-40) : ev.wheelDelta;
                    if(delta > 0) {
                        if($('#layout.one-page-scroll .vc-row-wrapper.slick-slide.slick-active').prev('.slick-slide').length > 0) {
                            ev.preventDefault();
                            $('#layout.one-page-scroll').slickPrev();
                        }
                    } else {
                        if($('#layout.one-page-scroll .vc-row-wrapper.slick-slide.slick-active').next('.slick-slide').length > 0) {
                          if (ev.preventDefault) {
							ev.preventDefault();
                            $('#layout.one-page-scroll').slickNext();
						  }
						  else {
							  ev.returnValue = false;
						  }
						}
						  }
                        }, 150, true)
                    }
                });
                 $('body').keyup(function(e) {
                    if (e.keyCode == 38 || e.keyCode == 37) {
                        if($('#layout.one-page-scroll .vc-row-wrapper.slick-slide.slick-active').prev('.slick-slide').length > 0) {
                            $('#layout.one-page-scroll').slickPrev();
                        }
                    }
                    if (e.keyCode == 40 || e.keyCode == 39) {
                        if($('#layout.one-page-scroll .vc-row-wrapper.slick-slide.slick-active').next('.slick-slide').length > 0) {
                            $('#layout.one-page-scroll').slickNext();
                        }
                    }
                });

                var recalcValues = function() {
                    var heightOffset = 0;
                    var widthOffset = 0;
                    if($('body').hasClass('admin-bar')) {
                        heightOffset = $('#wpadminbar').outerHeight();
                    }
                    if($('#main-wrap').hasClass('dfd-custom-padding-html')) {
                        heightOffset += 40;
                        widthOffset = 40;
                    }
                    width = $(window).width() - widthOffset;
                    height = $(window).height() - heightOffset;
                    $('#main-wrap').css({
                        width : width,
                        height : height
                    }).find('#layout.one-page-scroll .vc-row-wrapper.slick-slide').css({
                        width : width,
                        maxWidth : width,
                        height : height,
                        maxHeight : height
                    });
                    $('#layout.one-page-scroll .vc-row-wrapper.slick-slide >.row').addClass('dfd-vertical-aligned');
                };

                recalcValues();
                $(window).on('load resize', recalcValues);
            }

		});
	}
})(jQuery);