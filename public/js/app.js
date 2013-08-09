App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.set('errormessage', '');

App.Router.map(function() {
  this.resource('fields', function(){
	  this.route('add'),
	  this.resource('fields.edit', { path: '/fields/:field_id'}),
	  this.resource('fields.remove', { path: '/remove/:field_id'});
  });
  
  this.resource('contacts', function(){
	  this.route('new'),
	  this.resource('contacts.show', { path: '/show/:contact_id'}),
	  this.resource('contacts.edit', { path: '/edit/:contact_id'}),
	  this.resource('contacts.delete', { path: '/delete/:contact_id'});
  });
});

/* STORE */
App.Store = DS.Store.extend({
	revision: 13,
	adapter: DS.FixtureAdapter.extend({
        queryFixtures: function(fixtures, query, type) {
            console.log(query);
            console.log(type);
            return fixtures.filter(function(item) {
                for(prop in query) {
                    if( item[prop] != query[prop]) {
                        return false;
                    }
                }
                return true;
            });
        }
    })
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
	}.property('type')
});

App.FieldsAddController = Ember.ObjectController.extend({
		
	save: function() {
		exist = App.Field.find().filterProperty('name', this.get('name'));
		if(exist.get("length") === 1 && this.get('name').toLowerCase() !== 'email' && this.get('name').toLowerCase() !== 'nombre' && this.get('name').toLowerCase() !== 'apellido'){
			if (this.get('values') != undefined) { 
				this.set('values', 
				this.get('values').split('\n')
				);
			}

			this.get("model.transaction").commit();
			App.set('errormessage', '');
			this.get("target").transitionTo("fields.index");
		} else {
			App.set('errormessage', 'El campo ya existe');
			this.get("target").transitionTo("fields.add");
		}
	},
		
	cancel: function(){
		 this.get("transaction").rollback();
		 App.set('errormessage', '');
		 this.get("target").transitionTo("fields");
	}	
});


App.FieldsEditController = Ember.ObjectController.extend({
	edit: function() {
	exist = App.Field.find().filterProperty('name', this.get('name'));
		if(exist.get("length") === 1 && this.get('name').toLowerCase() !== 'email' && this.get('name').toLowerCase() !== 'nombre' && this.get('name').toLowerCase() !== 'apellido'){
			if (this.get('values') != undefined) { 
				this.set('values', 
				this.get('values').split('\n')
				);
			}

			this.get("model.transaction").commit();
			App.set('errormessage', '');
			this.get("target").transitionTo("fields.index");
		} else {
			App.set('errormessage', 'El campo ya existe!');
			this.get("target").transitionTo("fields.edit");
		}
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 App.set('errormessage', '');
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
  { id: 2, name: 'Nombre', type: 'Text', required: true, values: '', defaultValue: '' },
  { id: 3, name: 'Apellido', type: 'Text', required: false, values: '', defaultValue: '' }
];

App.FieldsIndexRoute = Ember.Route.extend({
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

// App.FieldsController = Ember.ArrayController.extend();
App.FieldsIndexController = Ember.ArrayController.extend();
//Fin de todo lo que tenga que ver con los campos



//Inicio contactos
App.Contact = DS.Model.extend({
	email: DS.attr( 'string' ),
	name: DS.attr( 'string' ),
	lastName: DS.attr( 'string' ),
	status: DS.attr( 'number' ),
	activedOn: DS.attr('string'),
	bouncedOn: DS.attr('string'),
	subscribedOn: DS.attr('string'),
	unsubscribedOn: DS.attr('string'),
	spamOn: DS.attr('string'),
	ipActived: DS.attr('string'),
	ipSubscribed: DS.attr('string'),
	updatedOn: DS.attr('string'),
	createdOn: DS.attr('string'),
	isBounced: DS.attr('boolean'),
	isSubscribed: DS.attr('boolean'),
	isSpam: DS.attr('boolean'),
	isActived: DS.attr('boolean'),
	
	becameError: function() {
		return alert('there was an error!');
	},
	becameInvalid: function(errors) {
		return alert("Record was invalid because: " + errors);
	}
});

App.Contact.FIXTURES = [
  { id: 1, email: 'puertorro@hotmail.es', name: 'Fenicio', lastName: 'Cuantindioy', activedOn: 12345678, bouncedOn: 0, status: true, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 1225455524, ipActived: 13542532, ipSubscribed: 0, isBounced: false, isActived: true, isSpam: true, isSubscribed: true },
  { id: 2, email: 'lachicacandente@hotmail.es', name: 'Lola', lastName: 'Lolita', activedOn: 12345678, status: true, bouncedOn: 15544512, subscribedOn: 123456, unsubscribedOn: 15171518, spamOn: 0, ipActived: 561151515, ipSubscribed: 14822852, isBounced: true, isActived: true, isSpam: false, isSubscribed: false },
  { id: 3, email: 'superbigman@yahoo.es', name: 'Disney Alberto', lastName: 'Mosquera', activedOn: 0, status: false,bouncedOn: 0, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 0, ipActived: 0, ipSubscribed: 0, isBounced: false, isActived: false, isSpam: false, isSubscribed: false },
  { id: 4, email: 'yatusabe@live.com', name: 'Maicol Yovany', lastName: 'Icasa', activedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActived: 1528228, ipSubscribed: 0, isBounced: true, isActived: true, isSpam: true, isSubscribed: true }
];

//Controladores
App.ContactController = Ember.ObjectController.extend();


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

App.ContactsEditController = Ember.ObjectController.extend({
	edit: function() {
			this.get("model.transaction").commit();
			this.get("target").transitionTo("contacts");
		
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	}
});

App.ContactsDeleteController = Ember.ObjectController.extend({
    delete: function() {
		this.get('content').deleteRecord();
		this.get('store').commit();
		this.get("target").transitionTo("contacts");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	}
});


//Rutas

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.find();
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

App.ContactsEditRoute = Ember.Route.extend({
	deactivate: function () {
		console.log('Deactivate ContactsEdit');
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

App.ContactsShowController = Ember.ObjectController.extend({
	deactivated: function () {
		this.set("isActived", false);		
	},
	activated: function () {
		this.set("isActived", true);
	},
	unsubscribedcontact: function () {
		this.set("isSubscribed", false);
	},
	subscribedcontact: function () {
		this.set("isSubscribed", true);
	}
});


App.ContactsIndexController = Ember.ArrayController.extend({
	searchText: '',
    search: function(){
    console.log (App.Contact.find(this.get('searchText')));
    }
});

App.ContactsShowRoute = Ember.Route.extend({
});
