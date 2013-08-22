App = Ember.Application.create({
	rootElement: '#emberApplistContainer'
});

App.set('errormessage', '');

//Definiendo Rutas
App.Router.map(function() {
  this.resource('lists', function(){
	  this.route('new'),
	  this.resource('lists.edit', { path: '/edit/:list_id' });
  });
});

/* STORE */
// Serializador
var serializer = DS.RESTSerializer.create();

serializer.configure({
    meta: 'meta',
    pagination: 'pagination'
});

//Adaptador
App.Adapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: serializer
});

// Store (class)
App.Store = DS.Store.extend({
	revision: 13,
	adapter: App.Adapter.create()
//	adapter: DS.FixtureAdapter.extend({
//        queryFixtures: function(fixtures, query, type) {
//            console.log(query);
//            console.log(type);
//            return fixtures.filter(function(item) {
//                for(prop in query) {
//                    if( item[prop] != query[prop]) {
//                        return false;
//                    }
//                }
//                return true;
//            });
//        }
//    })
});

// Store (object)
App.store = App.Store.create();

//Creando el modelos

App.Dbase = DS.Model.extend({
    name: DS.attr('string'),
	lists: DS.hasMany('App.List')
});

App.List = DS.Model.extend({
	dbase: DS.belongsTo('App.Dbase'),
	name: DS.attr('string'),
	description: DS.attr( 'string' ),
	createdon: DS.attr('number'),
	updatedon: DS.attr('number')
});

App.DbaseIndexController = Ember.ArrayController.extend({
	model: function()
	{
		return App.store.findAll(App.Dbase);
	}
});


//Creando el fixture (parcial)
//App.List.FIXTURES = [
//	{id: 1, name: 'Mi primera Lista', description: 'Mi primera lista, no tiene descripcion alguna', createdon: '10 de agosto de 2012', updatedon: '12 de agosto de 2013'},
//	{id: 2, name: 'Mi segunda Lista', description: 'Mi seguna lista, no tiene descripcion alguna', createdon: '15 de marzo de 2013', updatedon: '16 de marzo de 2013'},
//	{id: 3, name: 'Mi tercera Lista', description: 'Mi tercera lista, no tiene descripcion alguna', createdon: '19 de febrero de 2013', updatedon: '19 de febrero de 2013'}
//];

//Rutas
App.ListsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.List.find();
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
		console.log('Deactivate ContactsListsEdit');
		this.doRollBack();
	},
	contextDidChange: function() {
        console.log('Cambio de modelo');
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

//Controladores
App.ListController = Ember.ObjectController.extend();

App.ListsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	getModelMetadata: function() {
		return App.store.typeMapFor(App.List);
	},
	
	refreshModel: function (obj) {
		var result = App.List.find(obj);
		this.set('content', result);
	}
//	,
//	searchText: '',
//    search: function(){
//		var resultado = App.Contact.find({ email: this.get('searchText') });
//		console.log(resultado);
//		this.set('content', resultado);
//	}

});

App.ListsNewController = Ember.ObjectController.extend({
	save: function(){
		if(this.get('name')==null){
			App.set('errormessage', 'El campo nombre esta vacío, debes ingresar un nombre');
			this.get("target").transitionTo("lists.new");
		}
		else{
			exist = App.List.find({name: this.get('name'), limit: 0});
			console.log(exist);
			App.resultado = exist;
				if(exist.get("length") == 0) {
					this.get('model.transaction').commit();
					App.set('errormessage', '');
					this.get("target").transitionTo("lists");
				}
				else {
					console.log(exist.get('firstObject').name);
					App.set('errormessage', 'El nombre de la lista ya se encuentra guardado, por favor escoge otro');
					this.get("target").transitionTo("lists.new");
				}
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
