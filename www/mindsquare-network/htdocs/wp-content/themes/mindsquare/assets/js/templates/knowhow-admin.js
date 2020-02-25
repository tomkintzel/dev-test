( function( $ ) {
	'use strict';

	// Lade alle Seminare
	var seminar_posts = '';
	$.ajax({
		url: ajaxurl,
		data: {
			'action': 'load_seminar_posts'
		},
		method: 'POST',
		dataType: 'JSON',
		async: false,
		success: function( data ) {
			seminar_posts = '';
			$.each( data, function( key, value ) {
				seminar_posts += '<option value="' + key + '">' + value + '</option>';
			});
		}
	});

	tinyMCE.create( 'tinymce.plugins.msq_knowhow_seminar', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} editor Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function( editor, url ) {
			// Register a button that opens plugin window
			editor.addButton( 'msq_knowhow_seminar_button', {
				icon: 'fa fa fa-graduation-cap',
				title: 'Seminar hinzufügen',
				cmd: 'msq_knowhow_seminar_window'
			});

			// Register the plugin window
			editor.addCommand( 'msq_knowhow_seminar_window', function() {
				tinyMCE.activeEditor.windowManager.open({
					title: 'Schulung hinzufügen',
					width: 550,
					height: 150,
					html: '<div class="msq-admin window">' +
							'<label for="msq-knowhow-seminar">Schulung:</label>' +
							'<select id="msq-knowhow-seminar">' +
								seminar_posts +
							'</select>' +
							'<label for="msq-knowhow-seminar-floating">Ausrichtung</label>' +
							'<select id="msq-knowhow-seminar-floating">' +
								'<option value="">Block</option>' + 
								'<option value="float-left">Links</option>' + 
								'<option value="float-right">Rechts</option>' + 
							'</select>' +
						'</div>',
					buttons: [{
						text: 'Hinzufügen',
						onclick: function() {
							if( $( '#msq-knowhow-seminar' ).val().length ) {
								var seminar = $( '#msq-knowhow-seminar option:selected' ).val();
								var floating = $( '#msq-knowhow-seminar-floating option:selected' ).val();
								tinyMCE.activeEditor.selection.setContent( '[msq-embed-seminar id=' + seminar + ' class="' + floating + '"]' );
							}
							tinyMCE.activeEditor.windowManager.close();
						}
					}, {
						text: 'Schließen',
						onclick: 'close'
					}]
				});
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} name Name of the control to create.
		 * @param {tinymce.ControlManager} controlManager Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl: function( name, controlManager ) {
			return null;
		}
	});

	// Register plugin
	tinyMCE.PluginManager.add( 'msq_knowhow_seminar', tinyMCE.plugins.msq_knowhow_seminar );
})( jQuery );
