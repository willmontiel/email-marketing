Ember.SaveHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function(p, message) {
		actuallyHandlePromise(p, message, this.showGritter);
	},

	handleSavePromiseAppError: function(p, message) {
		actuallyHandlePromise(p, message, this.showAppError);	
	},

	actuallyHandlePromise: function(p, message, callmeback) {
		var self = this;
		p.then(
			function() {
				$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 4000});
			}, 
			function(error) {
				if (error.status == 400 || error.status == 500) {
					try {
						var obj = $.parseJSON(error.responseText);
						callmeback(obj.error);
					}
					catch (e) {
					}
				}
			}
		);
	},

	showGritter: function(message) {
		$.gritter.add({title: 'Error', text: msg, sticky: false, time: 6000});
	},

	showAppError: function(message) {
		App.set('errormessage', msg);
	}
});