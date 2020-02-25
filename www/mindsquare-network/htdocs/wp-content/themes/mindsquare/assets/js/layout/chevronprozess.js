jQuery( function( $ ) {
	setTimeout( function() {
		// Chevronprozess Überschrift erhält die gleiche Höhe
		let heightHeadline = 0;
		jQuery(".chevronprozess-item > strong").each( function() {
			let elementSize = jQuery(this).outerHeight()
			if ( elementSize >= heightHeadline ) {
				heightHeadline = elementSize;
			}
		});

		jQuery(".chevronprozess-item > strong").height( heightHeadline );

		// Chevronprozess Pfeil wird vergrößert
		let styleBorderHeight = parseInt( window.getComputedStyle( jQuery( '.chevronprozess-item' )[0], ':after' ).getPropertyValue( 'border-top-width' ) );
		let itemHeight = jQuery(".chevronprozess-item").outerHeight();

		if ( styleBorderHeight <= Math.ceil( itemHeight / 2 ) ) {
			document.styleSheets[0].addRule('.chevronprozess-item:nth-last-child(n):after', 'border-top-width:' + Math.ceil( itemHeight / 2 ) + 'px !important; border-bottom-width:' + Math.ceil( itemHeight / 2 ) + 'px !important;');;
		}
	}, 1);
});