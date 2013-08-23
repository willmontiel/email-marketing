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
	  this.resource('contacts.delete', { path: '/delete/:contact_id'}),
	  this.route('newbatch');
  });
});

/* STORE */
// Serializador
var serializer = DS.RESTSerializer.create();

serializer.configure({
    meta: 'meta',
    pagination: 'pagination'
});

// Adaptador
App.Adapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: serializer
});

// Store (class)
App.Store = DS.Store.extend({
	revision: 13,
	adapter: App.Adapter.create()
	
//	adapter: DS.FixtureAdapter.extend({
//        queryFixtures: function(fixtures, query, type) {
//            console.log(query);
//            console.log(type);
//            return fixtures.filter(function(item) {
//                for(prop in query) {
//                    if( item[prop] != query[prop]) {
//                        return false;
//                    }
//                }
//                return true;
//            });
//        }
//    })
});

// Store (object)
App.store = App.Store.create();


//Inicio de todo lo que tenga que ver con los campos
App.Field = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number'),
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

//App.Field.FIXTURES = [
//  { id: 1, name: 'Email', type: 'Text', required: true, values: '', defaultValue: 'Ninguno' },
//  { id: 2, name: 'Nombre', type: 'Text', required: true, values: '', defaultValue: '' },
//  { id: 3, name: 'Apellido', type: 'Text', required: false, values: '', defaultValue: '' }
//];

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
		this.get('model.transaction').commit();
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
App.Contact = DS.Model.extend(
	myContactModel
);


App.List = DS.Model.extend({
    name: DS.attr('string'),
	lists: DS.hasMany('App.Contact')
});

//App.Contact.FIXTURES = [
//  { id: 1, email: 'puertorro@hotmail.es', name: 'Fenicio', lastName: 'Cuantindioy', activatedOn: 12345678, bouncedOn: 0, status: true, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 1225455524, ipActive: 13542532, ipSubscribed: 0, isBounced: false, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 2, email: 'lachicacandente@hotmail.es', name: 'Lola', lastName: 'Lolita', activatedOn: 12345678, status: true, bouncedOn: 15544512, subscribedOn: 123456, unsubscribedOn: 15171518, spamOn: 0, ipActive: 561151515, ipSubscribed: 14822852, isBounced: true, isActive: true, isSpam: false, isSubscribed: false },
//  { id: 3, email: 'superbigman@yahoo.es', name: 'Disney Alberto', lastName: 'Mosquera', activatedOn: 0, status: false,bouncedOn: 0, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 0, ipActive: 0, ipSubscribed: 0, isBounced: false, isActive: false, isSpam: false, isSubscribed: false },
//  { id: 5, email: 'yatusabe@live.com', name: 'Maicol Yovany', lastName: 'Icasa', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 6, email: 'elcoco@gmail.com', name: 'linux', lastName: 'bin', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 7, email: 'labebe@live.com', name: 'mac', lastName: 'var', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 8, email: 'ajam@live.com', name: 'Ubuntu', lastName: 'www', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 9, email: 'jj@jj.com', name: 'windows', lastName: 'ext', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 10, email: 'jojojo@live.com', name: 'fedora', lastName: 'dll', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 11, email: 'lol@live.com', name: 'kubuntu', lastName: 'query', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true }
//];

//Rutas

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.find();
	}
});

App.ContactsShowRoute = Ember.Route.extend({
});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.createRecord();
	},
	deactivate: function () {
		if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
			this.get('currentModel.transaction').rollback();
			console.log('Rollback new Contact!');
		}
	}
});

App.ContactsNewbatchRoute = Ember.Route.extend();

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
		if (model && model.get('isDirty') && !model.get('isSaving')) {
			model.get('transaction').rollback();
		}
	}
});

//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

App.ContactsNewController = Ember.ObjectController.extend({
	errors: null,
	
	save: function() {
		
		 exist = App.Contact.find().filterProperty('email', this.get('email'));
			if(exist.get("length") === 1){
				var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
				if(filter.test(this.get('email'))){
					this.get('model').set('isActive', true);
					this.get('model').set('isSubscribed', true);
					this.get("model.transaction").commit();
					App.set('errormessage', '');
					this.get("target").transitionTo("contacts");
				}
				else{
					App.set('errormessage', 'El email no es correcto');
					this.get("target").transitionTo("contacts.new");
				}
			}
			else{
				App.set('errormessage', 'El email ya existe');
				this.get("target").transitionTo("contacts.new");
			}
//		var model = this.get('model');
//		if (model.get('isValid') && !model.get('isSaving')) {
//			
//			model.set('isActive', true);
//			model.set('isSubscribed', true);
//			
//			model.on('becameInvalid', this, function() {
//				console.log('INVALID, INVALID Will Robinson!');
//				this.handleFailure();
//			});			
//			model.on('becameError', this, function() {
//				console.log('ERROR, ERROR Will Robinson!: ');
//				console.log(this.get('content.error'));
//				console.log(this.get('model.error'));
//				console.log(this.get('content.errors'));
//				console.log(this.get('model.errors'));
//				this.handleFailure();
//			});	
//			model.on('didCreate', this, function() {
//				this.get('target').transitionTo('contacts');
//			});
//			
//			model.get('transaction').commit();
//				}
	},
		
	cancel: function(){
		console.log('Cancelling!');
		this.get("target").transitionTo("contacts");
		console.log('Cancelled!');
	},

	handleFailure: function() {
		console.log('Handling failures!!!');
		window.errormsg = this.get('content.errors');
		console.log(errormsg);
		this.set('errors', errormsg);
		App.set('errormessage', errormsg);
		this.get('transaction').rollback();
		this.set('content', App.Contact.createRecord(this.get('toJSON')));
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
		this.get('model.transaction').commit();
		this.get("target").transitionTo("contacts");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	}
});

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	searchText: '',
    search: function(){
		var resultado = App.Contact.find({ email: this.get('searchText') });
		console.log(resultado);
		this.set('content', resultado);
	},
			
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Contact);
	},
	
	refreshModel: function (obj) {
		console.log('Retrieving!');
		var result = App.Contact.find(obj);
		console.log('Setting!');
		this.set('content', result);
		console.log('Set!');
	}
});

App.ContactsShowController = Ember.ObjectController.extend({
//	deactivated: function () {
//		this.set("isActive", false);		
//	},
//	activated: function () {
//		this.set("isActive", true);
//	},
	unsubscribedcontact: function () {
		this.set("isSubscribed", false);
		this.get('model.transaction').commit();
	},
	subscribedcontact: function () {
		this.set("isSubscribed", true);
		this.get('model.transaction').commit();
	}
});

//Views

App.ContactsNewView = Ember.View.extend({
  didInsertElement: function() {
        jQuery("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
});

App.ContactsEditView = Ember.View.extend({
  didInsertElement: function() {
        jQuery("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
});












