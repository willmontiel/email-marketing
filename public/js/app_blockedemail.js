App.Blocked = DS.Model.extend({
	email: DS.attr('string'),
    blockedReason: DS.attr('string'),
	blockedDate: DS.attr('number')
});

//App.Blocked.FIXTURES = [
//	{id: 1, email: 'lala@lala.com', blockedReason: 'HABEAS DATA', blockedDate: '12 de agosto de 2013'},
//	{id: 2, email: 'lolo@lolo.com', blockedReason: 'Queja del cliente',  blockedDate: '16 de marzo de 2013'},
//	{id: 3, email: 'lele@lele.com', blockedReason: 'HABEAS DATA',  blockedDate: '19 de febrero de 2013'}
//];

//Rutas
App.BlockedIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Blocked.find();
	}
});

App.BlockedBlockRoute = Ember.Route.extend({
	model: function(){
		return App.Blocked.createRecord();
	},
	deactivate: function () {
		if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
			this.get('currentModel.transaction').rollback();
			console.log('Rollback new Blocked Email!');
		}
	}
});

//Controladores
App.BlockedController = Ember.ObjectController.extend();

App.BlockedIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Blocked);
	},
	
	refreshModel: function (obj) {
		var result = App.Blocked.find(obj);
		this.set('content', result);
	}
});

App.BlockedBlockController = Ember.ObjectController.extend({
	block: function (){
		var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
		if(this.get('email') == null) {
			App.set('errormessage', 'El campo email esta vacío, por favor verifica la información');
			this.get("target").transitionTo("blocked.block");
		}
		else if (this.get('blockedReason') == null) {
			App.set('errormessage', 'Debes enviar al menos una razón de porqué estas bloqueando este email, por favor verifica la información');
			this.get("target").transitionTo("blocked.block");
		}
		else {
			if(filter.test(this.get('email'))) {
				this.get('model.transaction').commit();
				App.set('errormessage', '');
				this.get("target").transitionTo("blocked");
			}
			else {
				App.set('errormessage', 'El email que ingresaste es invalido, por favor verifica la información');
				this.get("target").transitionTo("blocked.block");
			}
		}
	},
	
	cancel: function(){
		this.get("transaction").rollback();
		App.set('errormessage', '');
		this.get("target").transitionTo("blocked");
	}
});

App.BlockedUnblockController = Ember.ObjectController.extend({	
	unblock: function() {
		this.get('content').deleteRecord();
		this.get('model.transaction').commit();
		this.get("target").transitionTo("blocked");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("blocked");
	}
});