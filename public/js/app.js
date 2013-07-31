App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.Router.map(function() {
  this.resource('fields', function(){
	  this.resource('add'),
	  this.resource('edit');
  });
  
});

/* STORE */
App.Store = DS.Store.extend({
  revision: 13,
  adapter: DS.FixtureAdapter.create()
});


App.Fields = DS.Model.extend({
	name: DS.attr( 'string' ),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean')
});

App.Fields.FIXTURES = [
  { id: 1, name: 'Nombre', type: 'Texto', required: false },
  { id: 2, name: 'Apellido' , type: 'Texto', required: false }
];

App.FieldsController = Ember.ArrayController.extend();