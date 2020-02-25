(function ($) {
	var downloads = [];

	$.ajax({
		url: ajaxurl,
		data: {
			'action': 'get_downloads'
		},
		method: 'POST',
		dataType: 'JSON',
		async: false,
		success: function (names) {
			for (var id in names) {
				if (names.hasOwnProperty(id)) {
					downloads.push({
						text: names[id],
						value: id
					});
				}
			}
		}
	});

	tinyMCE.create('tinymce.plugins.msq_download_embeds', {
		init: function (editor) {
			editor.addButton('msq_embed_download_btn', {
				title: 'Download einbetten',
				icon: 'fa fa-download',
				cmd: 'msq_embed_download_cmd'
			});

			editor.addCommand('msq_embed_download_cmd', function () {
				var host = window.location.hostname;
				tinyMCE.activeEditor.windowManager.open({
					title: 'Download einbetten',
					width: 550,
					height: 143,
					html:
						'<div class="msq-download-embeds-window window">' +
						'	<label for="msq-download-embeds-download">Download</label>' +
						'	<select id="msq-download-embeds-download">' +
						(host.includes('rz10.de') ? '		<option value="anhang">Anhang des Blogbeitrags</option>' : '') +
						(host.includes('rz10.de') ? '		<option value="video">Video als Anhang</option>' : '') +
						downloads.map(function (download) {
							return '<option value="' + download.value + '">' + download.text + '</option>';
						}).join('') +
						'	</select>' +
						'	<label for="msq-download-emebds-displaySetting">Darstellung</label>' +
						'	<select id="msq-download-embeds-displaySetting">' +
						'		<option value="full">Komplette Breite mit Formular</option>' +
						'		<option value="left">Linksbündig mit Bild und Link</option>' +
						'		<option value="right">Rechtsbündig mit Bild und Link</option>' +
						'	</select>' +
						'</div>',
					buttons: [{
						text: 'Hinzufügen',
						onclick: function () {
							var text = '[download id="' + $('#msq-download-embeds-download :selected').val() + '" display_setting="' + $('#msq-download-embeds-displaySetting :selected').val() + '"]';
							tinyMCE.activeEditor.selection.setContent(text);
							tinyMCE.activeEditor.windowManager.close();
						}
					}, {
						text: 'Schließen',
						onclick: 'close'
					}]
				});

				$('#msq-download-embeds-download').select2();
			});
		}
	});

	// Register plugin
	tinyMCE.PluginManager.add('msq_download_embeds', tinyMCE.plugins.msq_download_embeds);

})(jQuery);
