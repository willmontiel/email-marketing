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
App.DbaseIndexController = Ember.ArrayController.extend({
	model: function()
	{
		return this.store.find('dbase');
	}
});

/* Fixtures de prueba */
//App.List.FIXTURES = [
//	{id: 1, name: 'Mi primera Lista', description: 'Mi primera lista, no tiene descripcion alguna', createdon: '10 de agosto de 2012', updatedon: '12 de agosto de 2013'},
//	{id: 2, name: 'Mi segunda Lista', description: 'Mi seguna lista, no tiene descripcion alguna', createdon: '15 de marzo de 2013', updatedon: '16 de marzo de 2013'},
//	{id: 3, name: 'Mi tercera Lista', description: 'Mi tercera lista, no tiene descripcion alguna', createdon: '19 de febrero de 2013', updatedon: '19 de febrero de 2013'}
//];
//
//App.Dbase.FIXTURES = [
//	{id: 1, name: 'Base 1'},
//	{id: 2, name: 'Base 2'},
//	{id: 3, name: 'Base 3'}
//];

/* Rutas de Contact Lists */
App.ListsIndexRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('list');
	}
});

App.ListsNewRoute = Ember.Route.extend({
	model: function(){
		return App.List.createRecord();
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('transaction').rollback();
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

App.ListsNewController = Ember.ObjectController.extend({
	save: function(){
		if(this.get('name')==null){
			App.set('errormessage', 'El nombre de la lista es requerido');
		}
		else{
//			exist = App.List.find({name: this.get('name'), limit: 0});
//			console.log(exist);
//			App.resultado = exist;
//				if(exist.get("length") == 0) {
				var that = this;
				this.get('content').one('didCreate',
					function () {
						that.get("target").transitionTo("lists");
					}
				);
					this.get('model.transaction').commit();
//				}
//				else {
//					console.log(exist.get('firstObject').name);
//					App.set('errormessage', 'El nombre de la lista ya se encuentra guardado, por favor escoge otro');
//					this.get("target").transitionTo("lists.new");
//				}
		}
	},
			
	cancel: function(){
		this.get("transaction").rollback();
		App.set('errormessage', '');
		this.get("target").transitionTo("lists");
	}
});

App.ListsEditController = Ember.ObjectController.extend({
	edit: function() {
		if(this.get('name')== ""){
			App.set('errormessage', 'El campo nombre esta vacío, debes ingresar un nombre');
			this.get("target").transitionTo("lists.edit");
		}
		else{
			this.get("model.transaction").commit();
			App.set('errormessage', '');
			this.get("target").transitionTo("lists");
		}
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("lists");
	}
});

App.ListsDeleteController = Ember.ObjectController.extend({
	delete: function() {
		this.get('content').deleteRecord();
		this.get('model.transaction').commit();
		this.get("target").transitionTo("lists");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("lists");
	}
	
});