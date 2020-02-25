( function( $ ) {
    var requests = [];

    $.ajax({
		url: ajaxurl,
		data: {
			'action': 'get_requests'
		},
		method: 'POST',
		dataType: 'JSON',
		async: false,
		success: function (names) {
			for (var id in names) {
				if (names.hasOwnProperty(id)) {
					requests.push({
						text: names[id],
						value: id
					});
				}
			}
		}
	});
	tinyMCE.create( 'tinymce.plugins.msq_request_embeds', {
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
			editor.addButton( 'msq_plugin_request_embeds_button', {
				title: 'Anfrage einbetten',
				icon: 'fa fa-desktop',
				cmd: 'msq_plugin_request_embeds_window'
			});

			

			// Register the plugin window
			editor.addCommand( 'msq_plugin_request_embeds_window', function() {
				tinyMCE.activeEditor.windowManager.open({
					title: 'Anfrage einbetten',
					width: 550,
					height: 270,
						html: '<div class="msq-plugin-request-embeds window">' +
						'<label for="msq-plugin-request-embeds-name">Name (Dieser Text erscheint in der E-Mail an den Kunden)</label>' +
						'<input type="text "id="msq-plugin-request-embeds-name">' +
						'<div id="msq-plugin-request-embeds-quote">' +
							'<label for="msq-plugin-request-embeds-quote-text">Zitat Text</label>' +  
							'<input type="text" id="msq-plugin-request-embeds-quote-text" name="msq-plugin-request-embeds-quote-text">' + 
						'</div>' +
						'<label title="Kann auch frei gelassen werden" for="msq-plugin-request-embeds-button-text">Button Text</label>' +  
						'<input type="text" placeholder="z.B. mehr erfahren" id="msq-plugin-request-embeds-button-text" name="msq-plugin-request-embeds-button-text">' +                    
					'</div>',
					buttons: [{
						text: 'Hinzufügen',
						onclick: function() {
							var text = '[request name="' + 
							$('#msq-plugin-request-embeds-name').val() + 
							'" request_text="' +
							$('#msq-plugin-request-embeds-quote-text').val() +
							'" button_text="' +
							$('#msq-plugin-request-embeds-button-text').val() +
							'"]';
							tinyMCE.activeEditor.selection.setContent(text);
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
		createControl: function( editor, controlManager ) {


			return null;
		}
	});

	// Register plugin
	tinyMCE.PluginManager.add( 'msq-request-embeds', tinyMCE.plugins.msq_request_embeds );
})( jQuery );
