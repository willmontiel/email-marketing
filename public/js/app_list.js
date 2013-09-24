/* Rutas de Contact Lists */
App.Router.map(function() {
  this.resource('lists', function(){
	  this.route('new'),
	  this.resource('lists.edit', { path: '/edit/:list_id' });
	  this.resource('lists.delete', { path: '/delete/:list_id' });
  }),
  
  this.resource('blockedemails', function(){
	  this.route('block');
	  this.resource('blockedemails.unblock', { path: '/unblock/:blockedemail_id' });
  });
  
  this.resource('segments', function(){
	  this.route('new');
	  this.resource('segments.delete', {path: '/delete/:segment_id'});
  });
});

/* Controladores de Dbase  (necesario?) */
App.DbaseController = Ember.ArrayController.extend({
	model: function() {
		return this.store.find('dbase');
	}
});

/* Rutas de Contact Lists */
App.ListsIndexRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('list');
	}
});

App.ListsNewRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('list');
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('model').rollback();
		}
	}
});

App.ListsEditRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.get('transaction').rollback();
		}
	}
});



/* Controladores de Contact Lists */
App.ListController = Ember.ObjectController.extend();

App.ListsIndexController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin,{
	init: function () 
	{
		this.set('acl', App.contactListACL);
	},
	modelClass : App.List,
	needs: ['dbase']
	
});

App.ListsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions: {
		edit: function() {
			if(this.get('name') == ""){
				App.set('errormessage', 'El campo nombre esta vacío, debes ingresar un nombre');
				this.transitionToRoute("lists.edit");
			}
			else{
				this.handleSavePromise(this.content.save(), 'lists', 'Se ha actualizado la lista!');
				App.set('errormessage', '');
			}
		},
		cancel: function(){
			 this.get('model').rollback();
			 this.transitionToRoute("lists");
		}
	}
});

App.ListsDeleteController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	actions: {
		delete: function() {
			var list = this.get('content');
			list.deleteRecord();
			
			this.handleSavePromise(list.save(), 'lists', 'Se ha eliminado la lista');
		},
		cancel: function(){
			this.get('model').rollback();
			this.transitionToRoute("lists");
		}
	}
});