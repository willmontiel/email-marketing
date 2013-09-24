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
	},
	actions : {
		deactivate: function () {
			if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
				this.get('currentModel.transaction').rollback();
			}
		}
	}
});

App.BlockedemailsBlockRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('blockedemail');
	},
		
	actions : {
		deactivate: function () {
			if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
				this.currentModel.get('model').rollback();
			}
		}
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

App.BlockedemailsBlockController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
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
					App.set('errormessage', '');
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

App.BlockedemailsUnblockController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{	
	actions: {
		unblock: function() {
			var block = this.get('model');
			block.deleteRecord();
			
			this.handleSavePromise(block.save(), 'blockedemails', 'Dirección de correo electrónico desbloqueada!');
		},
				
		cancel: function(){
			 this.transitionToRoute('blockedemails');
		}
	}
});