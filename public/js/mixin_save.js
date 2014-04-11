Ember.SaveHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function(p, message) {
		var self = this;
		p.then(function() {
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 4000});
		}, 
		function(error) {
			if (error.status == 400 || error.status == 500) {
				try {
					var obj = $.parseJSON(error.responseText);
					$.gritter.add({title: 'Error', text: obj.errors, sticky: false, time: 6000});
				}
				catch (e) {
				}
			}
		});
	}
});