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
	values: DS.attr('text'),
	
	changedRequired: function() {
		this.get("transaction").commit();
		console.log("Transaction was commited");
	}.observes("required")
});

App.FieldsAddController = Ember.ObjectController.extend({
	save: function() {
		if (this.get('values') != undefined) { 
			this.set('values', 
			this.get('values').split(' ')
			);
		}
		else if (this.get('name') == undefined) { 
			this.set('name','Campo1');
		}
		this.get("model.transaction").commit();
		this.get("target").transitionTo("fields");
	},
		
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("fields");
	},
	isSelect: function() {
		return (this.get('type') == "Select" || this.get('type') == "MultiSelect");
	}.property('type')
	
});


App.FieldsEditController = Ember.ObjectController.extend({
	save: function() {
		if (this.get('values') != undefined) { 
			this.set('values', 
			this.get('values').split(' ')
			);
		}
		
		this.get("model.transaction").commit();
		this.get("target").transitionTo("fields");
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("fields");
	}
});

App.FieldController = Ember.ObjectController.extend();

App.types = [
  Ember.Object.create({type: "Texto", id: "Text"}),
  Ember.Object.create({type: "Fecha",    id: "Date"}),
  Ember.Object.create({type: "Numerico",    id: "Numerical"}),
  Ember.Object.create({type: "Area de texto",    id: "TextArea"}),
  Ember.Object.create({type: "Selección",    id: "Select"}),
  Ember.Object.create({type: "Selección Multiple",    id: "MultiSelect"})
];



App.Field.FIXTURES = [
  { id: 1, name: 'Nombre', type: "Text", required: false, values: 'vacio' },
  { id: 2, name: 'Apellido' , type: "Text", required: true, values: 'vacio' }
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
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("fields");
	}
});
App.FieldsController = Ember.ArrayController.extend();

