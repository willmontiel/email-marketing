App.Blockedemail = DS.Model.extend({
	email: DS.attr('string'),
    blockedReason: DS.attr('string'),
	blockedDate: DS.attr('string'),
	deleteContact: DS.attr('boolean')
});
//**
//** RUTAS **
//**
App.BlockedemailsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('blockedemail');
	}
});

App.BlockedemailsBlockRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('blockedemail');
	}
});

//**
//** CONTROLADORES **
//**
App.BlockedemailController = Ember.ObjectController.extend();

App.BlockedemailsIndexController = Ember.ArrayController.extend(Ember.MixinPagination, {
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Blockedemail);
	}
});

App.BlockedemailsBlockController = Ember.ObjectController.extend({
	actions: {
		block: function (){
			var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
			if(this.get('email') == null) {
				App.set('errormessage', 'El campo email esta vacío, por favor verifica la información');
				this.transitionToRoute('blockedemails.block');
			}
			else if (this.get('blockedReason') == null) {
				App.set('errormessage', 'Debes enviar al menos una razón de porqué estas bloqueando este email, por favor verifica la información');
				this.transitionToRoute('blockedemails.block');
			}
			else {
				if(filter.test(this.get('email'))) {
					var self = this;
					self.content.save().then(function(){
						self.transitionToROute('blockedemails')
					});
				}
				else {
					App.set('errormessage', 'El email que ingresaste es invalido, por favor verifica la información');
					this.transitionToRoute('blockedemails.block');
				}
			}
		},

		cancel: function(){
			this.get('model').rollback();
			this.transitionToRoute('blockedemails');
		}
	}
});

App.BlockedemailsUnblockController = Ember.ObjectController.extend({	
	actions: {
		unblock: function() {
			var self = this;
			var block = self.get('content');
			block.deleteRecord();
			
			block.save().then(function(){
				self.transitionToRoute('blockedemails');
			});
		},
				
		cancel: function(){
			 this.transitionToRoute('blockedemails');
		}
	}
});