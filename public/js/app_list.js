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
	  this.resource('segments.edit', { path: '/edit/:segment_id'});
	  this.resource('segments.delete', { path: '/delete/:segment_id'});
  });
  
   this.route('dbase');
});

/* Controladores de Dbase  (necesario?) *-*/
App.DbaseRoute = Ember.Route.extend({
  	model: function() {
		return this.store.find('dbase');
	}
});
App.DbaseController = Ember.ArrayController.extend({});

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
			this.currentModel.rollback();
		}
	},
			
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDbases();
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
			model.rollback();
		}
	}
});

/* Controladores de ContactLists */
App.ListController = Ember.ObjectController.extend();

App.ListsIndexController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin,{		
	selectedDbase: null,
	init: function () 
	{
		this.set('acl', App.contactListACL);
		var t = this;
		this.store.find('dbase').then(function(db) {
			var dbases = db.get('content');
			var values = [{id: 0, name:'Todas las Bases de Datos'}];
			for(var i = 0; i < dbases.length; i++) {
				var obj = {id: dbases[i].get('id'), name: dbases[i].get('name')};
				values.push(obj);
			}
			t.set('dbaseSelect', values);
		});
	},

	modelClass : App.List,
	needs: ['dbase'],

	dbaseSelect: [],
	
	dbaseSelectChange: function () {
		var idDbase = this.get('selectedDbase');
		var resultado = this.store.find('list', { dbase: idDbase });
		this.set('content', resultado);
    }.observes('selectedDbase'),
			
	actions: {
	}
});

App.ListsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	needs: ['dbase'],

	loadDbases: function () {
		this.get('controllers.dbase').set('content', this.store.find('dbase'));
	},
	
	actions: {
		save: function(){
			if(this.get('name') === null || this.get('name') === ''){
				this.set('errors.errormsg', 'El nombre de la lista es requerido');
				this.transitionToRoute('lists.new');
			}
			else if(this.get("dbase.id") == null) {
				this.set('errors.errormsg', 'Recuerde seleccionar una Base de Datos');
				this.transitionToRoute('lists.new');
			}
			else{
				this.set('errors.errormsg', '');
				this.handleSavePromise(this.content.save(), 'lists', 'Se ha creado la lista exitosamente');
			}
		},

		cancel: function(){
			App.set('errormessage', '');
			window.theDbaseController = this.get('controllers.dbase');
			this.get('model').rollback();
			this.transitionToRoute("lists");
		}
	}
});

App.ListsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions: {
		edit: function() {
			if(this.get('name') == ""){
				this.set('errors.errormsg', 'El nombre de la lista es requerido');
				this.transitionToRoute("lists.edit");
			}
			else{
				this.set('errors.errormsg', '');
				this.handleSavePromise(this.content.save(), 'lists', 'Se ha actualizado la lista!');
			}
		},
		cancel: function(){
			App.set('errormessage', '');
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