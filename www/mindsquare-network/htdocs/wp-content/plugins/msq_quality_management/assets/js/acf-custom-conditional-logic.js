(function($, undefined) {
	var CustomConditinalLogic = acf.Field.extend({
		type: 'custom_conditional_logic',
		events: {
			'click .add-custom-conditional-rule':      'onClickAddRule',
			'click .add-custom-conditional-group':     'onClickAddGroup',
			'click .remove-custom-conditional-rule':   'onClickRemoveRule',
		},
		onClickAddRule: function(e, $el) {
			this.addRule($el.closest('tr'));
		},
		onClickAddGroup: function(e, $el) {
			this.addGroup();
		},
		onClickRemoveRule: function(e, $el) {
			this.removeRule($el.closest('tr'));
		},

		addRule: function($tr) {
			acf.duplicate($tr);
		},
		removeRule: function($tr) {
			if($tr.siblings('tr').length == 0) {
				$tr.closest('.rule-group').remove();
			} else {
				$tr.remove();
			}
		},
		addGroup: function() {
			// vars
			var $group = this.$('.rule-group:last');

			// duplicate
			$group2 = acf.duplicate($group);

			// update h4
			$group2.find('h4').text('Oder');

			// remove all tr's except the first one
			$group2.find('tr').not(':first').remove();
		},
	});
	acf.registerFieldType(CustomConditinalLogic);
})(jQuery);
