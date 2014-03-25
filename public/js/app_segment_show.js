App = Ember.Application.create({
	rootElement: '#emberAppContactContainer'
});

//Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MySegmentUrl,
	serializer: App.ApplicationSerializer
});

// Store (class)
App.Store = DS.Store.extend();

//Inicio contactos
App.Contact = DS.Model.extend(
	myContactModel
);

//Definiendo Rutas
App.Router.map(function() {
	this.resource('contacts', function(){
		this.resource('contacts.delete', { path: '/delete/:contact_id'});
	});
});