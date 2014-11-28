Ember.SaveFormHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function(p, troute, message, fn) {
		var self = this;
		p.then(function(result) {
			self.transitionToRoute(troute, result.id);
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 3000});
			if (typeof fn == 'function') {
				fn();
			}
		}, function(error) {
			if (error.status == 422) {
				try {
					var obj = $.parseJSON(error.responseText);
					self.get("model").rollback();
					self.transitionToRoute(troute);
					$.gritter.add({title: 'Error', text: obj.error, sticky: false, time: 3000});
				}
				catch (e) {
				}
			}
			else {
				self.set('errors', {errormsg: error.statusText});
			}
		});
	}
});
