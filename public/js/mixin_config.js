Ember.SaveHandlerMixin = Ember.Mixin.create({
	/*
	 * Cuando hay un error muestra un gritter y luego redirige a "troute"
	 */
	handleSavePromise: function(p, troute, message, fn) {
//		console.log('Manejando promesa');
		this.actuallyHandlePromise(p, troute, message, fn, this.showGritter, false);
	},

	/*
	 * Cuando hay un error lo graba en App.errormessage y luego redirige a "troute"
	 */
	handleSavePromiseAppError: function(p, troute, message, fn) {
//		console.log('Manejando promesa');
		this.actuallyHandlePromise(p, troute, message, fn, this.showAppError, false);
	},


	/*
	 * Cuando hay un error muestra un gritter pero NO redirige a "troute"
	 */
	handleSavePromiseNoRollback: function(p, troute, message, fn) {
//		console.log('Manejando promesa');
		this.actuallyHandlePromise(p, troute, message, fn, this.showGritter, true);
	},

	/*
	 * Cuando hay un error lo graba en App.errormessage pero NO redirige a "troute"
	 */
	handleSavePromiseAppErrorNoRollback: function(p, troute, message, fn) {
//		console.log('Manejando promesa');
		this.actuallyHandlePromise(p, troute, message, fn, this.showAppError, true);
	},

	/*
	 * Este es el metodo que realmente hace el manejo de las promesas
	 */
	actuallyHandlePromise: function(p, troute, message, fn, callmeback, norollback) {
//		console.log('Manejando promesa DE VERDAD!');
		var self = this;

		p.then(function() {
			if (typeof self.get('errors.errormsg') !== 'undefined') {
				self.set('errors.errormsg', '');
			}
			self.transitionToRoute(troute);
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 3000});
			if (typeof fn == 'function') {
				fn();
			}
		}, function(error) {
				if (error.status == 422) {
					try {
						var obj = $.parseJSON(error.responseText);
						if (!norollback) {
							self.get("model").rollback();
							self.transitionToRoute(troute);
						}
						callmeback(obj.errors);
					}
					catch (e) {
					}
				}
				else {
					self.set('errors.errormsg', error.statusText);
				}
		});
	},

	/*
	 * Esta es la funcion que muestra el gritter de error
	 */
	showGritter: function(msg) {
		$.gritter.add({title: 'Error', text: msg, sticky: false, time: 5000});
	},

	/*
	 * Esta es la funcion que asigna el error
	 */
	showAppError: function(msg) {
		App.set('errormessage', msg);
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