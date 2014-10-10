App = Ember.Application.create({
	rootElement: '#emberAppContactContainer'
});

//Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MyContactlistUrl,
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
		this.route('new'),
		this.route('newbatch'),
		this.route('import'),
		this.route('newimport'),
		this.route('export'),
//			this.resource('contacts.show', { path: '/show/:contact_id'}),
//			this.resource('contacts.edit', { path: '/edit/:contact_id'}),
			this.resource('contacts.delete', { path: '/delete/:contact_id'});
	});
});

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	didInsertElement:function(){
		try{
			createCharts('ChartContainer', App.data, true, false);
		}
		catch(err){
			
		}
	}			
});