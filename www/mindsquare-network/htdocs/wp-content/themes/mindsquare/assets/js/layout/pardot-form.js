
jQuery( function( $ ) {
	$( '.pardotform.event-newsletter' ).on( 'load', function() {
		$( this ).off( 'load' ).on( 'load', function() {
			$( this ).closest( 'section' ).removeClass( function( index, className ) {
				match = className.match( /^mind-([\w-]*)/i );
				return match[ 0 ];
			}).addClass( 'mind-green-background' );
			$( this ).closest( '.row div' ).html( '<p class="h1 mb-3 text-center">Vielen Dank für Ihre Anmeldung!</p>' );
		});
	});
});
