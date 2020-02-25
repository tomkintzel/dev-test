(function ($) {
	let errorClass = 're-FieldGroup-Description-error';

	tinymce.create('tinymce.plugins.msqReferenceEmbeds', {
		init : function (editor, url) {
			editor.addButton('msqEmbedReference', {
				title: 'Referenz einbetten',
				cmd: 'msqEmbedReferenceCmd',
				icon: 'fa fa-commenting',
			});

			let options = '';

			editor.addCommand('msqEmbedReferenceCmd', function () {
				tinymce.activeEditor.windowManager.open({
					title: 'Referenz einbetten',
					minHeight: 400,
					html: `
<div class="re-Editor">
	<div class="re-FieldGroup">
		<label class="re-FieldGroup-Label" for="reference-with-text">Mit Text?</label>
		<p class="re-FieldGroup-Description">Soll der Slider mit oder ohne Text ausgespielt werden?</p>			
		<input class="re-FieldGroup-Field" id="reference-with-text" type="checkbox" checked/>
	</div>
	<div class="re-FieldGroup">
		<label class="re-FieldGroup-Label" for="reference-title">Titel</label>
		<p class="re-FieldGroup-Description">Wird über dem Slider ausgespielt</p>
		<input class="re-FieldGroup-Field" id="reference-title" type="text"/>
	</div>
	<div class="re-FieldGroup re-FieldGroup-vertical">
		<label class="re-FieldGroup-Label" for="reference-selection">Referenzen</label>
		<p id="re-selection-description" class="re-FieldGroup-Description">Mindestens 3 auswählen</p>
		<select class="re-FieldGroup-Field" id="reference-selection" multiple>
			${options}
		</select>
	</div>
</div>`,
					buttons: [
						{
							text: 'Hinzufügen',
							onclick: function() {
								let selectionDescription = document.querySelector('#re-selection-description');

								if (referenceSelection.select2('data').length < 3) {
									selectionDescription.classList.add(errorClass);
									return;
								}

								selectionDescription.classList.remove(errorClass);

								let idString = referenceSelection.val();
								let titleString = withText.checked && title.value ? title.value : '';
								let textString = withText.checked ? '' : ' with-text="false"';

								var text = `[referenzen_slider ids="${idString}"${textString}]${titleString}[/referenzen_slider]`;

								tinyMCE.activeEditor.selection.setContent(text);
								tinyMCE.activeEditor.windowManager.close();
							}
						},
						{
							text: 'Schließen',
							onclick: 'close'
						}
					]
				});

				let selectionDescription = document.querySelector('#re-selection-description');
				let withText = document.querySelector('#reference-with-text');
				let title = document.querySelector('#reference-title');
				let titleGroup = title.closest('.re-FieldGroup');
				let referenceSelection = $( '#reference-selection' );

				withText.addEventListener('click', (event) => {
					titleGroup.classList.toggle('hide');
				});

				referenceSelection.select2({
					theme: 'default re-FieldGroup-Field'
				});

				let appendOptions = (references) => {
					for (var id in references) {
						if (references.hasOwnProperty(id)) {
							let option = document.createElement('option');
							option.value = id;
							option.innerHTML = references[id];
							referenceSelection[0].appendChild(option);
						}
					}
				};

				if ( window.msqRe && window.msqRe.hasOwnProperty('references') ) {
					appendOptions(window.msqRe.references);
				} else {
					referenceSelection.prop('disabled', true);
					let oldDesc = selectionDescription.innerHTML;
					selectionDescription.innerHTML = 'Referenzen werden geladen';

					$.ajax( {
						url: ajaxurl,
						data: {
							'action': 'get_successes'
						},
						method: 'POST',
						dataType: 'JSON',
						async: true,
						success: function ( names ) {
							window.msqRe = {
								references: names
							};
							appendOptions(names);
							referenceSelection.prop('disabled', false);
							selectionDescription.innerHTML = oldDesc;
						}
					} );
				}
			});
		},
	});

	tinymce.PluginManager.add('msqReferenceEmbeds', tinymce.plugins.msqReferenceEmbeds);
})(jQuery);