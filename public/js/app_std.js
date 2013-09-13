App = Ember.Application.create({
	rootElement: '#emberApplistContainer'
});

/* STORE */
// Serializador
//App.Serializer = DS.RESTSerializer.extend({
//    meta: 'meta',
//    pagination: 'pagination'
//});

//Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MyDbaseUrl,
//	serializer: App.ApplicationSerializer
});

// Store (class)
App.Store = DS.Store.extend({});

// Store (object)
//App.store = App.Store.create();

