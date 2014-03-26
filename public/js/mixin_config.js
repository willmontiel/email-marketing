Ember.SaveHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function(p, troute, message, fn) {
		var self = this;
		p.then(function() {
			self.transitionToRoute(troute);
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 3000});
			if (typeof fn == 'function') {
				fn();
			}
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
	}.property(),
			
	allowBlockedemail: function() {
		if(this.acl !== 0 && this.acl.allowBlockedemail !== 0) {
			return false;
		}
		else{
			return true;
		}
	}.property(),
	
	allowContactlist: function() {
		if(this.acl !==0 && this.acl.allowContactlist !== 0) {
			return false;
		}
		else {
			return true;
		}
	}.property(),
	
	importBatchDisabled: function() {
		if(this.acl !== 0 && this.acl.canImportBatch !== 0){
			return false;
		}
		else {
			return true;
		}
	}.property(),
			
	importDisabled: function() {
		if(this.acl !== 0 && this.acl.canImport !== 0) {
			return false;
		}
		else {
			return true;
		}
	}.property()
});

Ember.TextField.reopen({
	attributeBindings: ["required"]	
});