Ember.SaveHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function(p, troute, message) {
		var self = this;
		p.then(function() {
			self.transitionToRoute(troute);
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 3000});
		}, function(error) {
			if (error.status == 422) {
				try {
					var obj = $.parseJSON(error.responseText);
					self.set('errors', obj.errors);
				}
				catch (e) {
				}
			}
			else {
//				console.log('Error: ' + error.statusText);
				self.set('errors', {errormsg: error.statusText});
			}
		});
	}
});

Ember.AclMixin = Ember.Mixin.create({		
	createDisabled: function() {
		if (this.acl !== 0 && this.acl.canCreate !== 0){
			return false;
		}
		else {
			return true;
		}
	}.property(),
	
	readDisabled: function() {
		if (this.acl !== 0 && this.acl.canRead !== 0){
			return false;
		}
		else {
			return true;
		}
	}.property(),
	
	updateDisabled: function() {
		if (this.acl !== 0 && this.acl.canUpdate !== 0){
			return false;
		}
		else {
			return true;
		}
	}.property(),

	deleteDisabled: function() {
		if (this.acl !== 0 && this.acl.canDelete !== 0){
			return false;
		}
		else {
			return true;
		}
	}.property()
			
});