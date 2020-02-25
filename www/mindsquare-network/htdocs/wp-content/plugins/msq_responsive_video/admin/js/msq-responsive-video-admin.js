( function( $ ) {
	'use strict';

	tinyMCE.create( 'tinymce.plugins.msq_responsive_video', {
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
			editor.addButton( 'msq_plugin_responsive_video_button', {
				title: 'Responsive-Video hinzufügen',
				icon: 'fa fa fa-video-camera',
				cmd: 'msq_plugin_responsive_video_window'
			});

			// Register the plugin window
			editor.addCommand( 'msq_plugin_responsive_video_window', function() {
				tinyMCE.activeEditor.windowManager.open({
					title: 'Responsive-Video hinzufügen',
					width: 550,
					height: 346,
					html: '<div class="msq-plugin-responsive-video window">' +
							'<label for="msq-plugin-responsive-video-link">Video-Link:</label>' +
							'<input id="msq-plugin-responsive-video-link" type="text" placeholder="Video-Link" required autofocus></input>' +
							'<label for="msq-plugin-responsive-video-aspect-ratio">Seitenverhältnis:</label>' +
							'<select id="msq-plugin-responsive-video-aspect-ratio">' +
								'<option value="16by9">16:9</option>' +
								'<option value="4by3">4:3</option>' +
							'</select>' +
							'<label for="msq-plugin-responsive-video-width">Breite:</label>' +
							'<div class="description">Standardmäßig wird das Video in 660px ausgespielt. Außer Du willst unbedingt eine andere Größe (Auf eigene Verantwortung)</div>' +
							'<input id="msq-plugin-responsive-video-width" type="text" placeholder="660px" required autofocus></input>' +
							'<label for="msq-plugin-responsive-video-seminar-floating">Ausrichtung</label>' +
							'<select id="msq-plugin-responsive-video-seminar-floating">' +
								'<option value="">Block</option>' + 
								'<option value="float-left">Links</option>' + 
								'<option value="float-right">Rechts</option>' + 
							'</select>' +
							'<label for="msq-plugin-responsive-video-policy">' +
								'<input type="checkbox" id="msq-plugin-responsive-video-policy" checked="checked" />' +
								'Untertitel standardmäßig anzeigen?' +
							'</label>' +
						'</div>',
					buttons: [{
						text: 'Hinzufügen',
						onclick: function() {
							if( $( '#msq-plugin-responsive-video-link' ).val().length ) {
								var link = $( '#msq-plugin-responsive-video-link' ).val();
								var aspect_ratio = $( '#msq-plugin-responsive-video-aspect-ratio option:selected' ).val();
								var youtube_id = link.match( /youtu(be\.com\/embed\/|\.be\/|be\.com\/watch\?v=)([^\?&"']*)/ );
								var load_policy = $( '#msq-plugin-responsive-video-policy' ).is( ':checked' ) ? '&cc_load_policy=1' : '';
								var link_attr = youtube_id != null && typeof youtube_id[ 2 ] != 'undefined' ? 'https://www.youtube.com/embed/' + youtube_id[ 2 ] + '?rel=0' + load_policy : link;
								var aspect_ratio_attr = $.inArray( aspect_ratio, [ '16by9', '4by3' ] ) ? aspect_ratio : '16by9';
								var width = $( '#msq-plugin-responsive-video-width').val();
								var floating = $( '#msq-plugin-responsive-video-seminar-floating option:selected' ).val();
								tinyMCE.activeEditor.selection.setContent( '[msq-plugin-responsive-video aspect="' + aspect_ratio_attr + '" link="' + link_attr + ( width ? '" width="' + width : '' ) + ( floating ? '" class="' + floating : '' ) + '"]' );
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
	tinyMCE.PluginManager.add( 'msq_responsive_video', tinyMCE.plugins.msq_responsive_video );
})( jQuery );
