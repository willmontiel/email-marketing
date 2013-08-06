App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.Router.map(function() {
  this.resource('fields', function(){
	  this.route('add'),
	  this.resource('fields.edit', { path: '/fields/:field_id'}),
	  this.resource('fields.remove', { path: '/remove/:field_id'});
  });
  
  this.resource('contacts', function(){
	  this.route('new')
  });
});

/* STORE */
App.Store = DS.Store.extend({
	revision: 13
//  ,
//  adapter: DS.FixtureAdapter.create()
});

DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl
});

//Inicio de todo lo que tenga que ver con los campos
App.Field = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	limitInferior: DS.attr('number'),
	limitSuperior: DS.attr('number'),
	maxLong: DS.attr('number'),
//	changedRequired: function() {
//	}.observes("required")
	becameError: function() {
		return alert('there was an error!');
	},
	becameInvalid: function(errors) {
		return alert("Record was invalid because: " + errors);
	},
	isSelect: function() {
		return (this.get('type') == "Select" || this.get('type') == "MultiSelect");
	}.property('type'),
			
	isText: function() {
		return (this.get('type') == "Text");
	}.property('type'),
	
	isNumerical: function() {
		return (this.get('type') == "Numerical");
	}.property('type'),
			
	isDate: function() {
		return (this.get('type') == "Date");
	}.property('type'),
	
	isEmail: function() {
		return (this.get('name') == "Email" || this.get('name') == "Nombre" || this.get('name') == "Apellido");
	}.property('name')
});

App.FieldsAddController = Ember.ObjectController.extend({
		
	save: function() {
		if (this.get('values') != undefined) { 
			this.set('values', 
			this.get('values').split('\n')
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


App.FieldsEditController = Ember.ObjectController.extend({
	edit: function() {
	if (this.get('values') != undefined) { 
			this.set('values', 
			this.get('values').split('\n')
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

Ember.TextField.reopen({
	attributeBindings: ["required"]	
});


App.types = [
  Ember.Object.create({type: "Texto", id: "Text"}),
  Ember.Object.create({type: "Fecha",    id: "Date"}),
  Ember.Object.create({type: "Numerico",    id: "Numerical"}),
  Ember.Object.create({type: "Area de texto",    id: "TextArea"}),
  Ember.Object.create({type: "Selección",    id: "Select"}),
  Ember.Object.create({type: "Selección Multiple",    id: "MultiSelect"})
];

App.FieldsRoute = Ember.Route.extend({
  model: function(){
   return App.Field.find();
  } 
});

App.FieldsEditRoute = Ember.Route.extend({
	deactivate: function () {
		console.log('Deactivate FieldsEdit');
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

App.FieldsAddRoute = Ember.Route.extend({
	model: function(){
		return App.Field.createRecord();
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('transaction').rollback();
		}
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
//Fin de todo lo que tenga que ver con los campos


App.Contact = DS.Model.extend({
	email: DS.attr( 'string' ),
	
});
