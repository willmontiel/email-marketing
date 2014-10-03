var set = Ember.set;

Ember.FileField = Ember.TextField.extend({
	type: 'file',
	attributeBindings: ['multiple'],
	multiple: false,
	change: function(e) {
		var input = e.target;
		if (!Ember.isEmpty(input.files)) {
		  set(this, 'files', input.files);
		}
	}
});
