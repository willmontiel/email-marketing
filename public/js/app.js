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
	  this.route('new');
  });
});

/* STORE */
App.Store = DS.Store.extend({
	revision: 13,
	adapter: DS.FixtureAdapter.create()
});

//DS.RESTAdapter.reopen({
//	namespace: MyDbaseUrl
//});

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

App.Field.FIXTURES = [
  { id: 1, name: 'Email', type: 'Text', required: true, values: '', defaultValue: 'Ninguno' },
  { id: 1, name: 'Nombre', type: 'Text', required: true, values: '', defaultValue: '' },
  { id: 1, name: 'Apellido', type: 'Text', required: false, values: '', defaultValue: '' }
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



//Inicio contactos
App.Contact = DS.Model.extend({
	email: DS.attr( 'string' ),
	name: DS.attr( 'string' ),
	lastName: DS.attr( 'string' ),
	status: DS.attr( 'string' ),
	
	becameError: function() {
		return alert('there was an error!');
	},
	becameInvalid: function(errors) {
		return alert("Record was invalid because: " + errors);
	}
});

App.Contact.FIXTURES = [
  { id: 1, email: 'puertorro@hotmail.es', name: 'Fenicio', lastName: 'Cuantindioy', status: 'Activo' },
  { id: 2, email: 'lachicacandente@hotmail.es', name: 'Lola', lastName: 'Lolita', status: 'Activo' },
  { id: 3, email: 'superbigman@yahoo.es', name: 'Disney Alberto', lastName: 'Mosquera', status: 'Inactivo' },
  { id: 4, email: 'yatusabe@live.com', name: 'Maicol Yovany', lastName: 'Icasa', status: 'Activo' }
];

App.ContactController = Ember.ObjectController.extend();

App.ContactsRoute = Ember.Route.extend({
  model: function(){
   return App.Contact.find();
  } 
});

App.status = [
  Ember.Object.create({st: "Activo", id: "Activo"}),
  Ember.Object.create({st: "Inactivo", id: "Inactivo"})
];

App.ContactsNewController = Ember.ObjectController.extend({
		
	save: function() {
		this.get("model.transaction").commit();
		this.get("target").transitionTo("contacts");
	},
		
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	}	
});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.createRecord();
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('transaction').rollback();
		}
	}
});
App.ContactsController = Ember.ArrayController.extend();