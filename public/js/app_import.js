App = Ember.Application.create({
	rootElement: '#emberAppImportContainer'
});

App.set('errormessage', '');

//Definiendo Rutas
App.Router.map(function() {
  this.resource('import', function(){
	  this.route('new')
  });
});

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

//Inicio importacion
App.Import = DS.Model.extend(
	myImportModel
);

App.Import.FIXTURES = [
	
];

App.ImportsIndexRoute = Ember.Route.extend({
	model: function(){
	 return App.Field.find();
	}
});

App.ImportsIndexController = Ember.ArrayController.extend();

