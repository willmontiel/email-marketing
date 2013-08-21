Applist = Ember.Application.create({
	rootElement: '#emberApplistContainer'
});

Applist.set('errormessage', '');

//Definiendo Rutas
Applist.Router.map(function() {
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
Applist.Adapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: serializer
});

// Store (class)
Applist.Store = DS.Store.extend({
	revision: 13,
	adapter: Applist.Adapter.create()
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
Applist.store = Applist.Store.create();

//Creando el modelos

Applist.Dbase = DS.Model.extend({
    name: DS.attr('string'),
	lists: DS.hasMany('Applist.List')
});

Applist.List = DS.Model.extend({
	dbase: DS.belongsTo('Applist.Dbase'),
	name: DS.attr('string'),
	description: DS.attr( 'string' ),
	createdon: DS.attr('number'),
	updatedon: DS.attr('number')
});

Applist.DbaseIndexController = Ember.ArrayController.extend({
	model: function()
	{
		return Applist.store.findAll(Applist.Dbase);
	}
});


//Creando el fixture (parcial)
//Applist.List.FIXTURES = [
//	{id: 1, name: 'Mi primera Lista', description: 'Mi primera lista, no tiene descripcion alguna', createdon: '10 de agosto de 2012', updatedon: '12 de agosto de 2013'},
//	{id: 2, name: 'Mi segunda Lista', description: 'Mi seguna lista, no tiene descripcion alguna', createdon: '15 de marzo de 2013', updatedon: '16 de marzo de 2013'},
//	{id: 3, name: 'Mi tercera Lista', description: 'Mi tercera lista, no tiene descripcion alguna', createdon: '19 de febrero de 2013', updatedon: '19 de febrero de 2013'}
//];

//Rutas
Applist.ListsIndexRoute = Ember.Route.extend({
	model: function(){
		return Applist.List.find();
	}	
});

Applist.ListsNewRoute = Ember.Route.extend({
	model: function(){
		return Applist.List.createRecord();
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('transaction').rollback();
		}
	}
});

Applist.ListsEditRoute = Ember.Route.extend({
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
Applist.ListController = Ember.ObjectController.extend();

Applist.ListsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	getModelMetadata: function() {
		return Applist.store.typeMapFor(Applist.List);
	},
	
	refreshModel: function (obj) {
		var result = Applist.List.find(obj);
		this.set('content', result);
	}

});

Applist.ListsNewController = Ember.ObjectController.extend({
	save: function(){
		if(this.get('name')==null){
			Applist.set('errormessage', 'El campo nombre esta vacío, debes ingresar un nombre');
			this.get("target").transitionTo("lists.new");
		}
		else{
			exist = Applist.List.find({name: this.get('name'), limit: 0});
			console.log(exist);
			Applist.resultado = exist;
				if(exist.get("length") == 0) {
					this.get('model.transaction').commit();
					Applist.set('errormessage', '');
					this.get("target").transitionTo("lists");
				}
				else {
					console.log(exist.get('firstObject').name);
					Applist.set('errormessage', 'El nombre de la lista ya se encuentra guardado, por favor escoge otro');
					this.get("target").transitionTo("lists.new");
				}
		}
	},
			
	cancel: function(){
		this.get("transaction").rollback();
		Applist.set('errormessage', '');
		this.get("target").transitionTo("lists");
	}
});

Applist.ListsEditController = Ember.ObjectController.extend({
	edit: function() {
			this.get("model.transaction").commit();
			this.get("target").transitionTo("lists");
		
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("lists");
	}
});
