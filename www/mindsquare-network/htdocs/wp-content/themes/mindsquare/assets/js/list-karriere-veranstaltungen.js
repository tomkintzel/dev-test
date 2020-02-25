jQuery( document ).ready(function(){
	jQuery( ".event-item-more" ).click( function() {
		jQuery( this ).find( 'i' ).toggleClass( 'fa-angle-down' ).toggleClass( 'fa-angle-up' );
	});
});
