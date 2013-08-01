App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.Router.map(function() {
  this.resource('fields', function(){
	  this.route('add'),
	  this.resource('fields.edit', { path: '/fields/:field_id'}),
	  this.resource('fields.remove', { path: '/remove/:field_id'});
  });
});

/* STORE */
App.Store = DS.Store.extend({
  revision: 13,
  adapter: DS.FixtureAdapter.create()
});


App.Field = DS.Model.extend({
	name: DS.attr( 'string' ),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	
	changedRequired: function() {
		this.get("transaction").commit();
		console.log("Transaction was commited");
	}.observes("required")
});

App.FieldsAddController = Ember.ObjectController.extend({
	save: function() {
		this.get("model.transaction").commit();
		this.get("target").transitionTo("fields");
	}
});

App.FieldsEditController = Ember.ObjectController.extend({
	save: function() {
		this.get("model.transaction").commit();
		this.get("target").transitionTo("fields");
	}
});

App.FieldController = Ember.ObjectController.extend();

App.types = [
  Ember.Object.create({type: "Texto", id: 1}),
  Ember.Object.create({type: "Fecha",    id: 2}),
  Ember.Object.create({type: "Numerico",    id: 3}),
  Ember.Object.create({type: "Area de texto",    id: 4}),
  Ember.Object.create({type: "Selección",    id: 5}),
  Ember.Object.create({type: "Selección Multiple",    id: 6})
];

App.Field.FIXTURES = [
  { id: 1, name: 'Nombre', type: 1, required: false },
  { id: 2, name: 'Apellido' , type: 1, required: true }
];

App.FieldsRoute = Ember.Route.extend({
  model: function(){
   return App.Field.find();
  } 
});

App.FieldsAddRoute = Ember.Route.extend({
  model: function(){
	  return App.Field.createRecord();
  } 
});
App.FieldsRemoveController = Ember.ObjectController.extend({
    eliminate: function() {
		this.get('content').deleteRecord();
		this.get('store').commit();
		this.get("target").transitionTo("fields");
    }
});
App.FieldsController = Ember.ArrayController.extend();

