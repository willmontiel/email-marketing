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

App.ListsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : 'list',
	needs: ['dbase']
});

App.ListsEditController = Ember.ObjectController.extend({
	actions: {
		edit: function() {
			if(this.get('name')== ""){
				App.set('errormessage', 'El campo nombre esta vacío, debes ingresar un nombre');
				this.transitionToRoute("lists.edit");
			}
			else{
				var self = this;
				self.content.save().then(function() {
					App.set('errormessage', '');
					self.transitionToRoute("lists");
				});
			}
		},
		cancel: function(){
			 this.get('model').rollback();
			 this.transitionToRoute("lists");
		}
	}
});

App.ListsDeleteController = Ember.ObjectController.extend({
	actions: {
		delete: function() {
			var self = this;
			var list = self.get('content');
			
			list.deleteRecord();
			
			list.save().then(function(){
				self.transitionToRoute('lists');
			});
		},
		cancel: function(){
			this.transitionTo('lists');	
		}
	}
});

//App.DbaseSelect = [
//	Ember.Object.create({customField: "Email", id: "email"}),
//	Ember.Object.create({customField: "Email", id: "email"}),
//];