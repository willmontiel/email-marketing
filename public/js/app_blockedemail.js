App.Blockedemail = DS.Model.extend({
	email: DS.attr('string'),
    blockedReason: DS.attr('string'),
	blockedDate: DS.attr('string'),
	deleteContact: DS.attr('boolean'),
	isDeny: function() {
		return true;
	}.property()
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

App.BlockedemailsIndexController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin, {
	init: function () 
	{
		this.set('acl', App.blockedemailACL);
	},
	modelClass : App.Blockedemail
});

App.BlockedemailsBlockController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, Ember.AclMixin, {
	init: function () 
	{
		this.set('acl', App.blockedemailACL);
	},
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
					this.handleSavePromise(this.content.save(), 'blockedemails', 'Correo bloqueado!');
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