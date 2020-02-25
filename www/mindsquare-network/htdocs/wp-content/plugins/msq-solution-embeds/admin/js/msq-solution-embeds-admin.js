( function( $ ) {
    var solutions = [];

    $.ajax({
		url: ajaxurl,
		data: {
			'action': 'get_solutions'
		},
		method: 'POST',
		dataType: 'JSON',
		async: false,
		success: function (names) {
			for (var id in names) {
				if (names.hasOwnProperty(id)) {
					solutions.push({
						text: names[id],
						value: id
					});
				}
			}
		}
	});
	tinyMCE.create( 'tinymce.plugins.msq_solution_embeds', {
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
			editor.addButton( 'msq_plugin_solution_embeds_button', {
				title: 'Angebot einbetten',
				icon: 'fa fa-shopping-bag',
				cmd: 'msq_plugin_solution_embeds_window'
			});

			

			// Register the plugin window
			editor.addCommand( 'msq_plugin_solution_embeds_window', function() {
				tinyMCE.activeEditor.windowManager.open({
					title: 'Angebot einbetten',
					width: 550,
					height: 350,
						html: '<div class="msq-plugin-solution-embeds window">' +
						'<label for="msq-plugin-solution-embeds-solution">Angebot:</label>' +
						'<select id="msq-plugin-solution-embeds-solution">' +
						solutions.map(function (solution) {
							return '<option value="' + solution.value + '">' + solution.text + '</option>';
						}).join('') +
						'</select>' +
						'<label for="msq-plugin-solution-embeds-look-and-feel">Frontend-Aussehen</label>' +
						'<select id="msq-plugin-solution-embeds-look-and-feel">' +
							'<option value="small">Angebot schmall</option>' +
							'<option value="full">Angebot vollständig</option>' +
							'<option value="quote">Angebot Zitat Freitext</option>' +
							'<option value="custom">Angebot schmall custom</option>' +
						'</select>' +   
						'<div style="display:none;" id="msq-plugin-solution-embeds-quote">' +
							'<label for="msq-plugin-solution-embeds-quote-text">Zitat Text</label>' +  
							'<input type="text" id="msq-plugin-solution-embeds-quote-text" name="msq-plugin-solution-embeds-quote-text">' + 
						'</div>' +
						'<div style="display:none;" id="msq-plugin-solution-embeds-custom">' +
							'<label for="msq-plugin-solution-embeds-custom-headline">Custom Überschrift</label>' +  
							'<input type="text" id="msq-plugin-solution-embeds-custom-headline" name="msq-plugin-solution-embeds-custom-headline">' + 
							'<label for="msq-plugin-solution-embeds-custom-text">Custom Text</label>' +  
							'<input type="text" id="msq-plugin-solution-embeds-custom-text" name="msq-plugin-solution-embeds-custom-text">' + 
						'</div>' +
						'<label title="Kann auch frei gelassen werden" for="msq-plugin-solution-embeds-button-text">Button Text</label>' +  
						'<input type="text" placeholder="z.B. mehr erfahren" id="msq-plugin-solution-embeds-button-text" name="msq-plugin-solution-embeds-button-text">' +                    
					'</div>',
					buttons: [{
						text: 'Hinzufügen',
						onclick: function() {
							var text = '[solution id="' + 
							$('#msq-plugin-solution-embeds-solution :selected').val() + 
							'" display_setting="' + 
							$('#msq-plugin-solution-embeds-look-and-feel :selected').val() +
							'" quote_text ="' +
							$('#msq-plugin-solution-embeds-quote-text').val() +
							'" button_text ="' +
							$('#msq-plugin-solution-embeds-button-text').val() +
							'" headline ="' +
							$('#msq-plugin-solution-embeds-custom-headline').val() +
							'" body ="' +
							$('#msq-plugin-solution-embeds-custom-text').val() +
							'"]';
							tinyMCE.activeEditor.selection.setContent(text);
							tinyMCE.activeEditor.windowManager.close();
						}
					}, {
						text: 'Schließen',
						onclick: 'close'
					}]
				});
				$('#msq-plugin-solution-embeds-look-and-feel').on('change', function(){
					if(this.value == 'quote'){
						$('#msq-plugin-solution-embeds-quote').show();
					}else{
						$('#msq-plugin-solution-embeds-quote').hide();
					}

					if(this.value == 'custom'){
						$('#msq-plugin-solution-embeds-custom').show();
					}else{
						$('#msq-plugin-solution-embeds-custom').hide();
					}
				});
				var content = $('#msq-plugin-solution-embeds-look-and-feel :selected').val();
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
	tinyMCE.PluginManager.add( 'msq-solution-embeds', tinyMCE.plugins.msq_solution_embeds );
})( jQuery );
