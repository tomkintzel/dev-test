jQuery(function() {
	jQuery('.header-col-left').after('<div class="header-col-left header-contact-wrapper"></div>').next().html(jQuery('.header-contact.move').removeClass('d-none'));
	jQuery('.btn-cta-navi-employment, .btn-cta-navi').remove();
});
