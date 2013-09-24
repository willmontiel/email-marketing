App = Ember.Application.create({
	rootElement: '#emberApplistContainer'
});

//Adaptador
//App.ApplicationAdapter = DS.RESTAdapter.extend();

DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	plurals: {dbase: 'dbasess'}
});

//DS.RESTAdapter.configure('plurals', {
//		dbase: 'dbases'
//	});

// Store (class)
App.Store = DS.Store.extend({});

// Store (object)
//App.store = App.Store.create();

